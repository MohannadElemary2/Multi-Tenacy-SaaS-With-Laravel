<?php

namespace App\Traits;

use App\Exports\ExcelExport;
use App\Http\Resources\FailureResource;
use App\Jobs\SendExportedExcel;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Modules\UserManagementSystem\Entities\Client\TenantUser;

trait HasExcelExport
{
    /**
     * Generate Excel File
     *
     * @param string $model
     * @param array $fields
     * @param string $filter
     * @param array $scopes
     * @param array $relations
     * @param string $fileName
     * @return void
     * @author Mohannad Elemary
     */
    public function exportToExcel($model, $fields, $filter = null, $scopes = [], $relations = [], $fileName = null)
    {
        // IF No Data To Export, Abort Request
        if (!$model::filter(app($filter))->addScopes($scopes)->count()) {
            abort(new FailureResource([], __('messages.empty_data_in_excel_export'), Response::HTTP_BAD_REQUEST));
        }

        // Generate Excel File Name
        $class_name = array_slice(explode("\\", $model), -1)[0];

        $excel_path = Str::random(5);
        $fileName   = $fileName ? "$fileName.xlsx" : $class_name ."s_$excel_path.xlsx";
        $excel_path = "excel/". $fileName;

        $user = TenantUser::find(auth()->id());
        // Generate Excel File and Send Email Notification With The Generated File
        (new ExcelExport($model, $fields, $filter, $scopes, $relations))->store($excel_path, config('filesystems.default'))
            ->chain(
                [
                new SendExportedExcel($user, $excel_path)
                ]
            );
    }
}

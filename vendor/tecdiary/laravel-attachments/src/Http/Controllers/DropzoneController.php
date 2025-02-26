<?php

namespace Tecdiary\Laravel\Attachments\Http\Controllers;

use Log;
use Lang;
use Event;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Tecdiary\Laravel\Attachments\Contracts\AttachmentContract;

class DropzoneController extends Controller
{
    /**
     * Attachment model
     *
     * @var AttachmentContract
     */
    protected $model;

    public function __construct(AttachmentContract $model)
    {
        $this->model = $model;
    }

    public function delete($id, Request $request)
    {
        try {
            if ($file = $this->model->where('uuid', $id)->first()) {
                /** @var AttachmentContract $file */

                if ($file->model_type || $file->model_id) {
                    return response(Lang::get('attachments::messages.errors.delete_denied'), 422);
                }

                if (filter_var(config('attachments.behaviors.dropzone_check_csrf'), FILTER_VALIDATE_BOOLEAN) &&
                    $file->metadata('dz_session_key') !== csrf_token()
                ) {
                    return response(Lang::get('attachments::messages.errors.delete_denied'), 401);
                }

                if (false === Event::dispatch('attachments.dropzone.deleting', [$request, $file], true)) {
                    return response(Lang::get('attachments::messages.errors.delete_denied'), 403);
                }

                $file->delete();
            }

            return response('', 204);
        } catch (Exception $e) {
            Log::error('Failed to delete attachment : ' . $e->getMessage(), ['id' => $id, 'trace' => $e->getTraceAsString()]);

            return response(Lang::get('attachments::messages.errors.delete_failed'), 500);
        }
    }

    public function post(Request $request)
    {
        if (false === Event::dispatch('attachments.dropzone.uploading', [$request], true)) {
            return response(Lang::get('attachments::messages.errors.upload_denied'), 403);
        }

        $file = $this->model
            ->fill(
                Arr::only(
                    $request->input(),
                    config('attachments.attributes')
                )
            )
            ->fromPost($request->file($request->input('file_key', 'file')));

        $file->metadata = ['dz_session_key' => csrf_token()];

        try {
            if ($file->save()) {
                return Arr::only($file->toArray(), config('attachments.dropzone_attributes'));
            }
        } catch (Exception $e) {
            Log::error('Failed to upload attachment : ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }

        return response(Lang::get('attachments::messages.errors.upload_failed'), 500);
    }
}

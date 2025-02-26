<?php

namespace Tecdiary\Laravel\Attachments\Http\Controllers;

use Lang;
use Crypt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Tecdiary\Laravel\Attachments\Contracts\AttachmentContract;

class ShareController extends Controller
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

    public function download($token, Request $request)
    {
        try {
            $data = json_decode(Crypt::decryptString($token));
        } catch (DecryptException $e) {
            abort(404, Lang::get('attachments::messages.errors.file_not_found'));

            return;
        }

        $id = $data->id;
        $expire = $data->expire;

        if (Carbon::createFromTimestamp($expire)->isPast()) {
            abort(403, Lang::get('attachments::messages.errors.expired'));
        }

        if (property_exists($data, 'disposition')) {
            $disposition = 'inline' === $data->disposition ? $data->disposition : 'attachment';
        }

        if ($file = $this->model->where('uuid', $id)->first()) {
            try {
                /** @var AttachmentContract $file */
                if (!$file->output($disposition)) {
                    abort(403, Lang::get('attachments::messages.errors.access_denied'));
                }
            } catch (FileNotFoundException $e) {
                abort(404, Lang::get('attachments::messages.errors.file_not_found'));
            }
        }

        abort(404, Lang::get('attachments::messages.errors.file_not_found'));
    }
}

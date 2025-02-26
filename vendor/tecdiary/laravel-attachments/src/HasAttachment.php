<?php

namespace Tecdiary\Laravel\Attachments;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tecdiary\Laravel\Attachments\Contracts\AttachmentContract;

trait HasAttachment
{
    /**
     * @param UploadedFile|string $fileOrPath
     * @param array               $options Set attachment options : title, description, key, disk
     *
     * @return Attachment|null
     * @throws \Exception
     */
    public function attach($fileOrPath, $options = [])
    {
        if (!is_array($options)) {
            throw new \Exception('Attachment options must be an array');
        }

        if (empty($fileOrPath)) {
            throw new \Exception('Attached file is required');
        }

        $attributes = Arr::only($options, config('attachments.attributes'));

        if (!empty($attributes['key']) && $attachment = $this->attachments()->where('key', $attributes['key'])->first()) {
            $attachment->delete();
        }

        /** @var Attachment $attachment */
        $attachment = app(AttachmentContract::class)->fill($attributes);
        $attachment->filepath = !empty($attributes['filepath']) ? $attributes['filepath'] : null;

        if (is_resource($fileOrPath)) {
            if (empty($options['filename'])) {
                throw new \Exception('resources required options["filename"] to be set?');
            }

            $attachment->fromStream($fileOrPath, $options['filename']);
        } elseif ($fileOrPath instanceof UploadedFile) {
            $attachment->fromPost($fileOrPath);
        } else {
            $attachment->fromFile($fileOrPath);
        }

        if ($attachment = $this->attachments()->save($attachment)) {
            return $attachment;
        }

        return null;
    }

    /**
     * Find an attachment by key
     *
     * @param string $key
     *
     * @return Attachment|null
     */
    public function attachment($key)
    {
        return $this->attachments->where('key', $key)->first();
    }

    /**
     * Get the attachments relation morphed to the current model class
     *
     * @return MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(get_class(app(AttachmentContract::class)), 'model');
    }

    /**
     * Find all attachments with the given
     *
     * @param string $group
     *
     * @return Attachment[]|Collection
     */
    public function attachmentsGroup($group)
    {
        return $this->attachments->where('group', $group);
    }
}

<?php

namespace App\Actions\Tec;

use App\Models\Checkin;
use Illuminate\Support\Facades\DB;

class PrepareOrder
{
    protected $attachments;

    protected $data;

    protected $form;

    protected $model;

    protected $updating;

    public function __construct($form, $attachments, $model, $updating = false)
    {
        $this->form = $form;
        $this->model = $model;
        $this->updating = $updating;
        $this->attachments = $attachments;
    }

    public function process()
    {
        $this->data = (new OrderData())($this->form, $this->model);

        return $this;
    }

    public function save()
    {
        DB::transaction(function () {
            $this->model->fill($this->data)->save();
            $relation_children = [];
            $relation_children[] = ['field' => 'id', 'relation' => 'serials', 'sync' => true, 'assoc' => false];
            $relation_children[] = ['field' => 'id', 'relation' => 'variations', 'sync' => true, 'assoc' => false];
            $this->model->syncHasMany($this->data['items'], 'items', 'id', true, $relation_children, ($this->model instanceof Checkin));
            $this->model->saveAttachments($this->attachments);
            $this->model->refresh();
        }, 2);

        return $this->model;
    }
}

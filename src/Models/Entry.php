<?php

namespace Origami\Models;

use Illuminate\Database\Eloquent\Model;

use Origami\Models\Field;
use Origami\Models\Entry;

class Entry extends Model
{

    public $table = 'origami_entries';

    protected $fillable = [
        'position',
    ];

    /**
     * Save a module
     */
    public function save(array $options = array())
    {
        if(!$this->uid) $this->uid = origami_uid($this->table);
        if(!$this->position) $this->position = $this->module->entries()->max('position') + 1;
        parent::save($options);
        return $this;
    }

    /**
     * Match Parent
     */
    public function attachToParent($uid)
    {
        $parent = Entry::where('uid', $uid)->first()->data()->where('field_id', $this->module->field->id)->first()->id;
        $this->data_id = $parent;
        $this->save();
    }

    /**
     * Get default field value
     */
    public function getDefaultFieldValueAttribute()
    {
        $field = $this->module->fields()->whereIn('type',['text','textarea'])->orderBy('default','DESC')->orderBy('position','ASC')->first();
        if(!$field) return $this->uid;
        if(!$this->data()->where('field_id',$field->id)->exists()) return '-';

        return $this->data()->where('field_id',$field->id)->first()->value;
    }

    /**
     * Fetch value for entry with given
     */
    public function fetchDataValueWithField(Field $field)
    {
        if(!is_null(old($field->identifier))) return old($field->identifier);
        $data = $this->data()->where('field_id',$field->id)->first();
        return $data ? $data->value : null;
    }

    /**
     * Fetch checkbox state with field
     */
    public function fetchCheckboxStateWithField(Field $field)
    {
        if(!is_null(old($field->identifier))) return old($field->identifier);
        $data = $this->data()->where('field_id',$field->id)->first();
        if($data) return $data->value;
        // Check options
        if(!empty($field->options['checkbox']['checked'])) return $field->options['checkbox']['checked'];
    }

    /**
     * Fetch checkbox state with field
     */
    public function fetchImagesWithField(Field $field)
    {
        $data = $this->data()->where('field_id',$field->id)->first();
        return $data ? ($data->images()->count() ? $data->images()->orderBy('position','ASC')->get() : []) : [];
    }

    /**
     * Fetch checkbox state with field
     */
    public function fetchImageListWithField(Field $field)
    {
        $data = $this->data()->where('field_id',$field->id)->first();
        return $data ? ($data->images()->count() ? implode(',',$data->images->pluck('uid')->toArray()) : '') : '';
    }

    /**
     * Get submodule entries
     */
    public function submoduleEntries(Field $field)
    {
        $data = $this->data()->where('field_id',$field->id)->first();
        if($data) return $data->field->submodule->entries()->where('data_id',$data->id)->get();
    }

    /**
     * Belongs to a module
     */
    public function module()
    {
        return $this->belongsTo('Origami\Models\Module');
    }

    /**
     * Has many data
     */
    public function data()
    {
        return $this->hasMany('Origami\Models\Data');
    }

    /**
     * Belongs to a parent data field
     */
    public function parent()
    {
        return $this->belongsTo('Origami\Models\Data', 'data_id');
    }

}

<?php

namespace Encore\Admin\Models;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class Menu.
 *
 * @property int $id
 *
 * @method where($parent_id, $id)
 * @method static insert(array $array)
 * @method static truncate()
 */
class Menu extends Model
{
    use DefaultDatetimeFormat;
    use SoftDeletes;
    use ModelTree {
            ModelTree::boot as treeBoot;
        }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'order',
        'title',
        'icon',
        'uri',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.menus_table'));

        parent::__construct($attributes);
    }
}

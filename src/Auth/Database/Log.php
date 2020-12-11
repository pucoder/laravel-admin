<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use DefaultDatetimeFormat;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'operate',
        'path',
        'method',
        'ip',
        'input'
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

        $this->setTable(config('admin.database.logs_table'));

        parent::__construct($attributes);
    }

    /**
     * Log belongs to users.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

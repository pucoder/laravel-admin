<?php

use Illuminate\Support\MessageBag;

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     *
     * @param string $path
     * @param mixed  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function admin_url($path = '', $parameters = [], $secure = null)
    {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }

        $secure = $secure ?: (config('admin.https') || config('admin.secure'));

        return url(admin_base_path($path), $parameters, $secure);
    }
}

if (!function_exists('admin_base_path')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_base_path($path = '')
    {
        $prefix = '/'.trim(config('admin.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        $path = trim($path, '/');

        if (is_null($path) || strlen($path) == 0) {
            return $prefix ?: '/';
        }

        return $prefix.'/'.$path;
    }
}

if (!function_exists('admin_toastr')) {

    /**
     * Flash a toastr message bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     */
    function admin_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new MessageBag(get_defined_vars());

        session()->flash('toastr', $toastr);
    }
}

if (!function_exists('admin_success')) {

    /**
     * Flash a success message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_success($title, $message = '')
    {
        admin_info($title, $message, 'success');
    }
}

if (!function_exists('admin_error')) {

    /**
     * Flash a error message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_error($title, $message = '')
    {
        admin_info($title, $message, 'error');
    }
}

if (!function_exists('admin_warning')) {

    /**
     * Flash a warning message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_warning($title, $message = '')
    {
        admin_info($title, $message, 'warning');
    }
}

if (!function_exists('admin_info')) {

    /**
     * Flash a message bag to session.
     *
     * @param string $title
     * @param string $message
     * @param string $type
     */
    function admin_info($title, $message = '', $type = 'info')
    {
        $message = new MessageBag(get_defined_vars());

        session()->flash($type, $message);
    }
}

if (!function_exists('admin_asset')) {

    /**
     * @param $path
     *
     * @return string
     */
    function admin_asset($path)
    {
        return (config('admin.https') || config('admin.secure')) ? secure_asset($path) : asset($path);
    }
}

if (!function_exists('admin_trans')) {

    /**
     * Translate the given message.
     *
     * @param string $key
     * @param array  $replace
     * @param string $locale
     *
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function admin_trans($key = null, $replace = [], $locale = null)
    {
        $line = __($key, $replace, $locale);

        if (!is_string($line)) {
            return $key;
        }

        return $line;
    }
}

if (!function_exists('array_delete')) {

    /**
     * Delete from array by value.
     *
     * @param array $array
     * @param mixed $value
     */
    function array_delete(&$array, $value)
    {
        $value = \Illuminate\Support\Arr::wrap($value);

        foreach ($array as $index => $item) {
            if (in_array($item, $value)) {
                unset($array[$index]);
            }
        }
    }
}

if (!function_exists('class_uses_deep')) {

    /**
     * To get ALL traits including those used by parent classes and other traits.
     *
     * @param $class
     * @param bool $autoload
     *
     * @return array
     */
    function class_uses_deep($class, $autoload = true)
    {
        $traits = [];

        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_unique($traits);
    }
}

if (!function_exists('admin_dump')) {

    /**
     * @param $var
     *
     * @return string
     */
    function admin_dump($var)
    {
        ob_start();

        dump(...func_get_args());

        $contents = ob_get_contents();

        ob_end_clean();

        return $contents;
    }
}

if (!function_exists('file_size')) {

    /**
     * Convert file size to a human readable format like `100mb`.
     *
     * @param int $bytes
     *
     * @return string
     *
     * @see https://stackoverflow.com/a/5501447/9443583
     */
    function file_size($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2).' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes.' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes.' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (!function_exists('prepare_options')) {

    /**
     * @param array $options
     *
     * @return array
     */
    function prepare_options(array $options)
    {
        $original = [];
        $toReplace = [];

        foreach ($options as $key => &$value) {
            if (is_array($value)) {
                $subArray = prepare_options($value);
                $value = $subArray['options'];
                $original = array_merge($original, $subArray['original']);
                $toReplace = array_merge($toReplace, $subArray['toReplace']);
            } elseif (strpos($value, 'function(') === 0) {
                $original[] = $value;
                $value = "%{$key}%";
                $toReplace[] = "\"{$value}\"";
            }
        }

        return compact('original', 'toReplace', 'options');
    }
}

if (!function_exists('json_encode_options')) {

    /**
     * @param array $options
     *
     * @return string
     *
     * @see http://web.archive.org/web/20080828165256/http://solutoire.com/2008/06/12/sending-javascript-functions-over-json/
     */
    function json_encode_options(array $options)
    {
        $data = prepare_options($options);

        $json = json_encode($data['options']);

        return str_replace($data['toReplace'], $data['original'], $json);
    }
}

if (!function_exists('admin_get_route')) {
    function admin_get_route(string $name): string
    {
        return config('admin.route.prefix').'.'.$name;
    }
}

//
if (! function_exists('admin_route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function admin_route($name, $parameters = [], $absolute = true)
    {
        $name = explode('.', $name);

        array_unshift($name, config('admin.route.as'));

        $name = implode('.', $name);

        return app('url')->route($name, $parameters, $absolute);
    }
}

if (!function_exists('admin_route_trans')) {
    /**
     * @param $route
     * @return string
     */
    function admin_route_trans($route)
    {
        $trans = [];
        foreach (explode('.', $route) as $string) {
            if ($string !== config('admin.route.as')) {
                $trans[] = trans('admin.' . $string);
            }
        }
        return implode('.', $trans);
    }
}

if (!function_exists('string_between')) {
    /**
     * @param string $strings
     * @param string $start_str
     * @param string $end_str
     * @param int $for_num
     * @param string $symbol
     * @return string
     */
    function string_between($strings, $start_str, $end_str, $for_num = 0, $symbol = "-")
    {
        $switch = false;
        $string = '';
        $index = 0;

        for ($i = 0; $i < strlen($strings); $i++) {
            if (!$switch && substr($strings, $i, 1) === $start_str) {
                $switch = true;
                $index++;
                continue;
            }
            if ($switch && substr($strings, $i, 1) === $end_str) {
                $switch = false;
                if ($for_num && $index === $for_num) {
                    break;
                }
                $string .= $symbol;
            }
            if ($switch) {
                $string .= substr($strings, $i, 1);
            }
        }

        return rtrim($string, $symbol);
    }
}

if (!function_exists('string_add_rand')) {
    /**
     * 字符串每隔几位插入多少个随机字符或者数字
     *
     * @param string $string 需要插入随机数的字符串
     * @param int $interval 每隔多少位插入
     * @param int $num 插入多少个
     * @param boolean $is_str 插入的是字符串还是数字（true = 字符串，false = 数字）
     * @return string
     */
    function string_add_rand($string, $interval = 1, $num = 1, $is_str = true)
    {
        $array = str_split($string, $interval);

        $strings = '';
        foreach ($array as $value) {
            if ($is_str) {
                $pattern = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
                $str_rand = '';
                for ($i = 0; $i < $num; $i++) {
                    $str_rand .= $pattern[mt_rand(0, strlen($pattern) - 1)]; //生成php随机数
                }
                $rand = $str_rand;
            } else {
                $rand_start = 0;
                $rand_end = 9;
                if ($num > 1) {
                    $rand_start = 1 . str_repeat(0, ($num - 1));
                    $rand_end = str_repeat(9, $num);
                }
                $rand = rand($rand_start, $rand_end);
            }

            if (strlen($value) === $interval) {
                $strings .= $value . $rand;
            } else {
                $strings .= $value;
            }
        }

        return $strings;
    }
}

if (! function_exists('set_route_url')) {
    /**
     * 格式化路由地址（处理变量）
     *
     * @param $uri
     * @return mixed
     */
    function set_route_url($uri)
    {
        if (mb_strpos($uri, "{") !== false && mb_strpos($uri, "}") !== false) {
            $between = string_between($uri, "{", "}", 1);

            $uri = str_replace("{" . $between . "}", "*", $uri);
        }

        return $uri;
    }
}

if (!function_exists('admin_restore_path')) {
    /**
     * restore admin url.
     *
     * @param string $path
     * @return string
     */
    function admin_restore_path($path = '')
    {
        $new_path = [];
        foreach (explode('/', $path) as $value) {
            if ($value !== config('admin.route.prefix')) {
                array_push($new_path, $value);
            }
        }

        return $new_path ? implode('/', $new_path) : '/';
    }
}

if (!function_exists('admin_restore_route')) {
    function admin_restore_route($name)
    {
        $route = [];
        foreach (explode('.', $name) as $string) {
            if ($string !== config('admin.route.as')) {
                $route[] = $string;
            }
        }

        return implode('.', $route);
    }
}

if (!function_exists('get_request')) {
    /**
     * get request method and path
     *
     * @param object $route
     * @return string
     */
    function get_request($route)
    {
        $uri = admin_restore_path($route->uri);

        if (mb_strpos($uri, "{") !== false && mb_strpos($uri, "}") !== false) {
            $between = string_between($uri, "{", "}", 1);
            $uri = str_replace("{" . $between . "}", "*", $uri);
        }

        return $route->methods[0] . '=>' . $uri;
    }
}

if (!function_exists('get_routes')) {
    /**
     * 获取权限
     *
     * @return array
     */
    function get_routes()
    {
        $routes = [
            'all_permissions' => '*',
            'home' => '',
            'self_setting' => ''
        ];

        foreach (app('router')->getRoutes() as $route) {
            $uri = admin_restore_path($route->uri);

            if (isset($route->action['as'])) {
                if (!in_array($uri, config('admin.auth.excepts')) && mb_strpos($route->action['as'], config('admin.route.as')) !== false) {
                    $uri = set_route_url($uri);

                    $as = admin_restore_route($route->action['as']);
                    $routes[$as] = $route->methods[0] . '=>' . $uri;
                }
            }
        }

        return $routes;
    }
}

if (!function_exists('set_permissions')) {
    /**
     * 设置权限
     *
     * @return array
     */
    function set_permissions()
    {
        $routes = get_routes();
        $return = [];
        foreach ($routes as $key => $value) {
            $array_keys = explode('.', $key);

            $is_replace = false;

            foreach (config('admin.auth.merge', []) as $search => $replace) {
                if (in_array($search, $array_keys)) {
                    $array_keys = array_replace($array_keys, [array_search($search, $array_keys) => $replace]);

                    if (isset($routes[implode('.', $array_keys)])) {
                        $is_replace = true;
                    }
                    break;
                }
            }

            $as = [];
            foreach ($array_keys as $array_key) {
                $trans = trans('admin.' . $array_key);
                if (mb_strpos($trans, 'admin.') !== false) {
                    $trans = $array_key;
                }
                array_push($as, $trans);
            }

            if ($is_replace) {
                $return[implode('.', $as)] .= '&&' . $value;
            } else {
                $return[implode('.', $as)] = $value;
            }
        }

        return $return;
    }
}

if (!function_exists('group_permissions')) {
    /**
     * 路由分组
     *
     * @return array
     */
    function group_permissions()
    {
        $new_routes = [];
        foreach (set_permissions() as $keys => $values) {
            if (is_array($values) && empty($values)) {
                $new_routes[$keys] = [];
            } elseif (strpos($keys,'.') !== false) {
                $group = explode('.', $keys);
                $new_routes[$group[0]][$values] = $group[1];
            } else {
                $new_routes[$values] = $keys;
            }
        }

        return $new_routes;
    }
}

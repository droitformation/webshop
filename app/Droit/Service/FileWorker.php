<?php

namespace App\Droit\Service;

class FileWorker implements FileWorkerInterface{

    public function authorized()
    {
        $directories = \Storage::disk('files')->directories();
        $manager     = config('manager');
        $authorised  = array_diff($directories,$manager);

        return $authorised;
    }

    public function tree($source_dir, $directory_depth = 0, $hidden = FALSE, $onlydir = TRUE)
    {
        $authorized = $this->authorized();

        if ($fp = @opendir($source_dir)) {
            $filedata	= array();
            $new_depth	= $directory_depth - 1;
            $source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.')) {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file)) {
                    $filedata[$file] = $this->tree($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
                }
            }

            closedir($fp);
            return $filedata;
        }

        return FALSE;
    }

    public function manager()
    {
        $all = $this->tree('files',0,false,true);

        return array_walk_recursive_delete($all, function ($value, $key) {
            return in_array($key,config('manager')) ? true : false;
        });
    }

    public function isAuthorized($item, $key)
    {
        return $key;
    }

    public function listDirectoryFiles($dir)
    {
        $tree = \File::files($dir);

        return collect($tree)->groupBy(function ($file){
            return \Carbon\Carbon::createFromTimestamp($file->getMTime())->toDateString();
        })->map(function ($files) use ($dir) {
            return $files->map(function ($file) use ($dir) {
                return $dir.'/'.$file->getFilename();
            });
        })->sortKeysDesc()->toArray();
    }

    public function listActionFiles($dir)
    {
        echo '<ul>';
        foreach($dir as $name => $subdir)
        {
            if(is_array($subdir))
            {
                echo '<li class="sub">'.$name.'';
                    $this->listFiles($subdir);
                echo '</li>';
            }
            else
            {
                echo '<li>';
                echo '<form action="'.url('admin/file').'" method="POST"><input type="hidden" name="_method" value="DELETE">'. csrf_field();
                echo '<a target="_blank" href="'.secure_asset($name.'/'.$subdir).'">'.$name.'/'.$subdir.'</a>';
                echo '<input type="hidden" name="path" value="'.$name.'/'.$subdir.'" />';
                echo '<button class="btn btn-danger btn-xs pull-right" data-what="Supprimer" data-action="file" type="submit">x</button>';
                echo '<span class="clearfix"></span></form>';
                echo '</li>';
            }

        }
        echo '</ul>';

    }

    public function treeDirectories($directories, $path = '')
    {
        $key   = key($directories);
        $exist = (isset($directories[$key]) && is_array( $directories[$key] ) ? true : false);

        echo ($exist ? '<ul>' : '' );

        if($exist)
        {
            foreach($directories as $name => $subdir)
            {
                if(is_array($subdir))
                {
                    $parent = ($name == 'pictos' ? 'no-interaction' : '');

                    echo '<li>
                        <a class="node file-folder" data-parent="'.$parent.'" href="'.$path.$name.'"><i class="fa fa-folder-o"></i> &nbsp;'.$name.'</a>';
                        $this->treeDirectories($subdir, $path.$name.'/');
                    echo '</li>';
                }
            }
        }

        echo ($exist ? '</ul>' : '' );
    }

}
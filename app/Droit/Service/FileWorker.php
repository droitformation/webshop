<?php

namespace App\Droit\Service;

class FileWorker implements FileWorkerInterface{

    public function authorisized()
    {
        $directories = \Storage::disk('files')->directories();
        $manager     = config('manager');
        $authorised  = array_diff($directories,$manager);

        return $authorised;
    }

    public function tree($source_dir, $directory_depth = 0, $hidden = FALSE)
    {
        if ($fp = @opendir($source_dir))
        {
            $filedata	= array();
            $new_depth	= $directory_depth - 1;
            $source_dir	= rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ( ! trim($file, '.') OR ($hidden == FALSE && $file[0] == '.'))
                {
                    continue;
                }

                if (($directory_depth < 1 OR $new_depth > 0) && @is_dir($source_dir.$file))
                {
                    $filedata[$file] = $this->tree($source_dir.$file.DIRECTORY_SEPARATOR, $new_depth, $hidden);
                }
                else
                {
                    $filedata[] = $file;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return FALSE;

    }

    public function manager()
    {
        $authorisized = $this->authorisized();

        $all = $this->tree('files');

        if(!empty($all))
        {
            foreach($all as $dir => $sub)
            {
                if(in_array($dir, $authorisized))
                {
                    $files[$dir] = $sub;
                }
            }
        }

        return $files;
    }

    public function listDirectoryFiles($dir)
    {
        return $this->tree($dir);
    }

    public function listActionFiles($dir)
    {
        echo '<ul>';
        foreach($dir as $name => $subdir)
        {
            if(is_array($subdir))
            {
                echo '<li>'.$name.'';
                $this->listFiles($subdir);
                echo '</li>';
            }
            else
            {
                echo '<li>';
                    echo '<form action="'.url('admin/file').'" method="POST"><input type="hidden" name="_method" value="DELETE">'. csrf_field();
                    echo '<a target="_blank" href="'.asset($subdir).'">'.$subdir.'</a>';
                    echo '<input type="hidden" name="path" value="'.$subdir.'" />';
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
        $exist = (is_array( $directories[$key] ) ? true : false);

        echo ($exist ? '<ul>' : '' );

        if($exist)
        {
            foreach($directories as $name => $subdir)
            {
                if(is_array($subdir))
                {
                    $active = ($name == 'uploads' ? 'active' : '');

                    echo '<li>
                        <a class="'.$active.' node file-folder" href="'.$path.$name.'"><i class="fa fa-folder-o"></i> &nbsp;'.$name.'</a>';
                    echo '</li>';
                }
            }
        }

        echo ($exist ? '</ul>' : '' );
    }
}
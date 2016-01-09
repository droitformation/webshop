@inject('fileWorker', 'App\Droit\Service\FileWorkerInterface')

@if(!empty($files))
    <?php $fileWorker->treeDirectories($files,'files/'); ?>
@endif
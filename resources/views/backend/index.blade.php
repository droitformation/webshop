@extends('backend.layouts.master')
@section('content')

    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4><i class="fa fa-edit"></i> &nbsp;Derniers </h4>
                </div>
                <div class="panel-body">

                    <form>
                        <div class="file-upload-wrapper" data-name="file">
                            <button type="button" class="btn btn-default" id="file" data-toggle="modal" data-target="#uploadModal">Chercher</button>
                            <div class="file-input"></div>
                            <!-- Modal -->
                            <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModal">
                                <div class="modal-dialog modal-dialog-big" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Choisir un fichier</h4>
                                        </div>
                                        <div class="modal-body">

                                            @inject('fileWorker', 'App\Droit\Service\FileWorkerInterface')

                                            <div class="row">
                                                <div class="col-md-2">
                                                    <ul class="treeview">
                                                        <li><a href="#">Documents</a>
                                                            @if(!empty($files))
                                                                <?php $fileWorker->treeDirectories($files,'files/'); ?>
                                                            @endif
                                                        </li>
                                                    </ul>

                                                    <div id="dropzone" class="dropzone"></div>

                                                </div>
                                                <div class="col-md-10">
                                                    <div id="fileManager" data-path="uploads"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                    {{--<div class='examples'>
                        <div class='parent'>
                            <div class='wrapper'>
                                <div id='middle-defaults' class='wide'>
                                    <div>You can move these ele containers</div>
                                    <div>Moviossible</div>
                                </div>
                            </div>
                            <div class='wrapper'>
                                <div id='left-defaults' class='container_dd'>
                                    <div>You can move these elements between these two containers</div>
                                    <div>Moving them anywhere else isn't quite possible</div>
                                    <div>Anything can be moved around. That includes images, <a href='https://github.com/bevacqua/dragula'>links</a>, or any other nested elements.</div>
                                </div>
                                <div id='right-defaults' class='container_dd'>
                                    <div>There's also the possibility of moving elements around in the same container, changing their position</div>
                                    <div>This is the default use case. You only need to specify the containers you want to use</div>
                                    <div>More interactive use cases lie ahead</div>
                                    <div>Moving <code>&lt;input/&gt;</code> elements works just fine. You can still focus them, too. <input placeholder='See?' /></div>
                                    <div>Make sure to check out the <a href='https://github.com/bevacqua/dragula#readme'>documentation on GitHub!</a></div>
                                </div>
                            </div>
                        </div>

                    </div>--}}

                    <!-- TREEVIEW CODE -->


                </div>
            </div>
        </div>

    </div>

@stop
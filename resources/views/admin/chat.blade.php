@extends('layouts.add')

@section('pagetitle')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Chat</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Skote</a></li> --}}
                    <li class="breadcrumb-item active">Chat</li>
                </ol>
            </div>

        </div>
    </div>
</div>  
@endsection
@section('content')
<div class="d-lg-flex">
    @include('conversation.users',["users"=>$users])
    <div class="w-100 user-chat">
        <div class="card">
            <div class="p-4 border-bottom ">
                <div class="row">
                    <div class="col-md-4 col-9">
                        <h5 class="font-size-15 mb-1" id="to-id"></h5>
                        <p class="text-muted mb-0"><i class="mdi mdi-circle text-success align-middle me-1"></i> Active now</p>
                    </div>
                    <div class="col-md-8 col-3">
                        <ul class="list-inline user-chat-nav text-end mb-0">
                            <li class="list-inline-item d-none d-sm-inline-block">
                                <div class="dropdown">
                                    <button class="btn nav-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-search-alt-2"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-md">
                                        <form class="p-3">
                                            <div class="form-group m-0">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                                    
                                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                                    
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </div>


            <div>
                <div class="chat-conversation p-3">
                    <ul class="list-unstyled mb-0" data-simplebar style="max-height: 486px;">
                        <li> 
                            {{-- <div class="chat-day-title">
                                <span class="title">Today</span>
                            </div> --}}
                        {{-- </li>
                        <li>
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="conversation-name">Steven Franklin</div>
                                    <p>
                                        Hello!
                                    </p>
                                    <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i> 10:00</p>
                                </div> 
                            </div>
                        </li>

                        <li class="right">
                            <div class="conversation-list">
                                <div class="ctext-wrap">
                                    <div class="conversation-name">Henry Wells</div>
                                    <p>
                                        Hi, How are you? What about our next meeting?
                                    </p>

                                    <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i> 10:02</p>
                                </div>
                            </div>
                        </li> --}}

                        {{-- <li>
                            <div class="conversation-list">
                                <div class="dropdown">

                                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                      </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                                <div class="ctext-wrap">
                                    <div class="conversation-name">Steven Franklin</div>
                                    <p>
                                        Yeah everything is fine
                                    </p>
                                    
                                    <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i> 10:06</p>
                                </div>
                                
                            </div>
                        </li>

                        <li class="last-chat">
                            <div class="conversation-list">
                                <div class="dropdown">

                                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                      </a>
                                    <div class="dropdown-menu">
                                        
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                                <div class="ctext-wrap">
                                    <div class="conversation-name">Steven Franklin</div>
                                    <p>& Next meeting tomorrow 10.00AM</p>
                                    <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i> 10:06</p>
                                </div>
                                
                            </div>
                        </li>
                      
                        <li class=" right">
                            <div class="conversation-list">
                                <div class="dropdown">

                                    <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                      </a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                                <div class="ctext-wrap">
                                    <div class="conversation-name">Henry Wells</div>
                                    <p>
                                        Wow that's great
                                    </p>

                                    <p class="chat-time mb-0"><i class="bx bx-time-five align-middle me-1"></i> 10:07</p>
                                </div>
                            </div>
                        </li> --}}
                        
                        
                    </ul>
                </div>
                
                <div class="p-3 chat-input-section">
                    <form action="{{route('chat.store')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="position-relative">
                                    <input required type="text" name="message" id="" class="form-control chat-input" placeholder="Enter Message...">
                                    <input type="text" name="to_id" id="to_id" class="d-none">
                                    <div class="chat-input-links" id="tooltip-container">
                                        <ul class="list-inline mb-0">
                                            <li class="list-inline-item"><a href="javascript: void(0);" title="Emoji"><i class="mdi mdi-emoticon-happy-outline"></i></a></li>
                                            <li class="list-inline-item"><a href="javascript: void(0);" title="Images"><i class="mdi mdi-file-image-outline"></i></a></li>
                                            <li class="list-inline-item"><a href="javascript: void(0);" title="Add Files"><i class="mdi mdi-file-document-outline"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-rounded chat-send w-md waves-effect waves-light"><span class="d-none d-sm-inline-block me-2">Envoyer</span> <i class="mdi mdi-send"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>  
@endsection
@section('js')

@endsection
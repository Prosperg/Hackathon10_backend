<div class="chat-leftsidebar me-lg-4">
    <div class="">
        <div class="py-4 border-bottom">
            <div class="d-flex">
                <div class="flex-shrink-0 align-self-center me-3">
                    <img src="{{asset('assets/images/users/avatar-1.jpg')}}" class="avatar-xs rounded-circle" alt="">
                </div>
                <div class="flex-grow-1">
                    <h5 class="font-size-15 mb-1">{{Auth::user()->fname." ".Auth::user()->lname}}</h5>
                    <p class="text-muted mb-0"><i class="mdi mdi-circle text-success align-middle me-1"></i> Active</p>
                </div>

                <div>
                    <div class="dropdown chat-noti-dropdown active">
                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-bell bx-tada"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="search-box chat-search-box py-4">
            <div class="position-relative">
                <input type="text" class="form-control" placeholder="Search...">
                <i class="bx bx-search-alt search-icon"></i>
            </div>
        </div> --}}

        <div class="chat-leftsidebar-nav">
            {{-- <ul class="nav nav-pills nav-justified">
                <li class="nav-item">
                    <a href="#chat" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                        <i class="bx bx-chat font-size-20 d-sm-none"></i>
                        <span class="d-none d-sm-block">Chat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#groups" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <i class="bx bx-group font-size-20 d-sm-none"></i>
                        <span class="d-none d-sm-block">Groups</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#contacts" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                        <i class="bx bx-book-content font-size-20 d-sm-none"></i>
                        <span class="d-none d-sm-block">Contacts</span>
                    </a>
                </li>
            </ul> --}}
            <div class="tab-content py-4">
                <div class="tab-pane show active" id="chat">
                    <div>
                        <h5 class="font-size-14 mb-3">Recent</h5>
                        <ul class="list-unstyled chat-list" id="chate-liste" data-simplebar style="max-height: 410px;">
                            @foreach ($users as $user)
                                <li data-id="{{$user->id}}" id="chate-liste">
                                    <a href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 align-self-center me-3">
                                                <i class="mdi mdi-circle font-size-10"></i>
                                            </div>
                                            <div class="flex-shrink-0 align-self-center me-3">
                                                <img src="assets/images/users/avatar-2.jpg" class="rounded-circle avatar-xs" alt="">
                                            </div>
                                            
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate font-size-14 mb-1">{{ $user->fname." ".$user->lname}}</h5>
                                                <p class="text-truncate mb-0"></p>
                                            </div>
                                            <div class="font-size-11"></div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>
@section('js')
    <script>
        $(document).ready(function(){
            //recuperer toute les li contenu dans ul ayant la classe chat-list
            let listes = $("ul#chate-liste  li#chate-liste");
            
            
           
            //parcourir l'objet listes
            for (let liste of listes) {
                //ajouter un gestionaire d'evenement sur chaque li
                liste.addEventListener('click', function(){
                    
                    // recuperer l'id de li ayant l'evenement click
                    let id = this.getAttribute('data-id');
                    
                    var to_id = $("#to_id").val(id);

                    // envoyer un requette ajax pour recuperer le message
                    $.ajax({
                        url: "/conversation/"+id,
                        type: 'GET',
                        success: function(data){
                            
                            $("h5#to-id").html(data.fname+ " " +data.lname)
                        },
                        error: function(xhr, status, error){
                            console.log("erreur : "+ error);
                        }
                    });                    
                    
                });
            }
            
        });
    </script>
@endsection
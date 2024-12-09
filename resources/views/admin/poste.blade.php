@extends('layouts.add')

@section('pagetitle')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Poste</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    {{-- <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li> --}}
                    <li class="breadcrumb-item active">Poste</li>
                </ol>
            </div>

        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">		 
        <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h3 class="">Etablir le congé pour un employé </h3>

                        <form method="POST" action=""  enctype="multipart/form-data" >
                            @csrf

                            <div class="row form-group">
                                 <label for="employe" class="mx-3" >Employé <sup class="text-danger">*</sup></label><br/>
                                <div class="col-md-12">
                                    
                                    <select name="employe_id" id="" class="form-control">
                                        <option value=""selected>----selectionnez un employé----</option>
                                            
                                    </select>
                                    
                                    @error('employe_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-group"> 
                                <label for="jour" class="mx-3" > Début congé<sup class="text-danger">*</sup></label><br/>
                                <div class="col-md-12">
                                    
                                    <input type="date" name="cong_begine" class="form-control" id="">
                                    
                                    @error('cong_begine')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row form-group">
                                <label for="plage" class="mx-3">Fin congé<sup class="text-danger">*</sup></label>
                                <div class="col-md-12">
                                    <input type="date" name="cong_end" class="form-control" id="">
                                </div>
                            </div>
                           
                            <div>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>	                                     
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <a href="#" name="Ajouter"  data-toggle="modal" data-target="#add" class="btn btn-primary"><i class="fa fa-plus"></i> Ajouter une categorie</a>
            <a href="#" name="Ajouter"  data-toggle="modal" data-target="#addposte" class="btn btn-success"><i class="fa fa-plus"></i> Ajouter un poste</a>
        </div>
             
        <hr>
        <table class="table table-striped table-bordered basic-datatables" id="table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th> 
                    <th>Categorie</th> 
                    <th>Contenu</th>   
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                    $i = 0;
                    foreach ($postes as $poste):
                    $i++;
                ?>
                <tr>
                    <td></td>
                    <td></td>   
                    <td></td>          
                    <td></td>      
                    
                         
                    <td class="text-center">
                        <div class="modal fade" id="conge" tabindex="-1" role="dialog" aria-labelledby="conge" aria-hidden="false">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content py-4">
                                    <div class="modal-body text-center">
                                        <h3>Voulez-vous vraiment annuler ce congé ?</h3>

                                        <form action="" method="post">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" value="Oui" name="Oui" class="btn btn-success">

                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Non</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div> 
                        <a href="#" name="conge"  data-toggle="modal" data-target="#conge" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>
                        </a> 
                    </td>    
                </tr>
                <?php endforeach; ?>
            
            </tbody>
            
        </table>

    </div>
    <div class="modal fade" id="add_employe" tabindex="-1" role="dialog" aria-labelledby="add_employe" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3 class="">Ajouter un employé</h3>
        
                    <form method="POST" action=""  enctype="multipart/form-data" >
                        @csrf
        
                        <div class="row form-group"> <label for="name" class="mx-3" >Nom du l'employé <sup class="text-danger">*</sup></label><br/>
                            <div class="col-md-12">
                                
                                <input type="text" name="name" class="@error('name') is-invalid @enderror form-control">
                                
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-group"> <label for="email" class="mx-3" >E-mail<sup class="text-danger">*</sup></label><br/>
                            <div class="col-md-12">
                                
                                <input type="email" name="email" class="@error('email') is-invalid @enderror form-control">
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="typeuser" value="agenda">
                        <div class="row form-group">
                            <label for="password" class="mx-3">Mot de passe<sup class="text-danger">*</sup></label><br/>
                            <div class="col-md-12">
                                <input type="password" name="password" class="@error('password') is-invalid @enderror form-control">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong></strong>
                                        </span>
                                    @enderror
                            </div>
                        </div>
                        
                        <div class="row form-group">
                            <label for="email" class="mx-3">Confirmez le mot de passe<sup class="text-danger">*</sup></label><br/>
                            <div class="col-md-12">
                            <input type="password" name="password_confirmation" class="@error('password_confirmed') is-invalid @enderror form-control">
                                @error('password_confirmed')
                                    <span class="invalid-feedback" role="alert">
                                        <strong></strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
        
                        <div>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>	   
            
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
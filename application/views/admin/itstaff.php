<!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-body">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4><?= $title ?></h4>
                  </div>
                  <div class="card-body">
                     <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#exampleModal" style="margin-top: -56px;">Add IT Staff</button>
                       <!-- start messages --->
                          <div style="text-align: center">
                              <?php if($feedback =$this->session->flashdata('feedback')){
                                $feedback_class =$this->session->flashdata('feedbase_class');  ?>
                                    <div class="row">
                                      <div class="col-lg-12 col-lg-offset-2">
                                      <div class="alert alert-dismissible <?=  $feedback_class;?>">
                                      <?= $feedback ?>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                      </div>
                                      </div>
                                  </div>

                              
                                <?php }?>

                            </div>
                    <!-- end of messages  --->
                    <div class="table-responsive">
                      <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                        <thead>
                          <tr>
                            <th>S.#</th>
                            <th>Name Name</th>
                            <th>Status</th>
                            <th>action</th>
                          </tr>
                        </thead>
                        <?php  if($itstaff){ $count = 0;?>
                        <tbody>
                          <?php  foreach($itstaff as $oneByOne):?>
                            <td><?= ++$count?></td>
                            <td><?= $oneByOne->user_name ?></td>
                            <td><?= ($oneByOne->user_status == 1)? "Active": 'In-active' ?></td>
                            <td>
                              <a class="fa fa-edit btn btn-info btn-sm" title="Edit"></a>
                              <a class="fa fa-trash btn btn-danger btn-sm" title="In-active"></a>
                            </td>
                          </tr>
                        <?php endforeach;?>
                        </tbody>
                      <?php } ?>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        
      </div>

      <!-- start add IT Staff -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="formModal"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="formModal">Add IT Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form method="post" action="<?= base_url('admin/itstaff/add_itstaff')?>">
                  <div class="form-group">
                    <label>Username</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-user"></i>
                        </div>
                      </div>
                      <input type="text" class="form-control" placeholder="Username" name="username" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">
                          <i class="fas fa-lock"></i>
                        </div>
                      </div>
                      <input type="password" class="form-control" placeholder="Password" name="password" required="">
                    </div>
                  </div>
                  <button type="Submit" class="btn btn-primary m-t-15 waves-effect">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <!-- end   add IT Staff -->
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
                    <div class="table-responsive">
                      <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                        <thead class="">
                          <tr>
                            <th>District Name</th>
                            <th>Total Complaints</th>
                          </tr>
                        </thead>
                        <?php if($district_reports):?>
                            <tbody>
                            <?php foreach($district_reports as $onByOne):?>
                                <tr>
                                    <td><?= $onByOne->district_name?></td>
                                    <td><?= $onByOne->noofcomplaints?></td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>
                        <?php endif; ?>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
        

        <section class="content-header">
          <h1>Contact Us Reply</h1>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
              <!-- general form elements disabled -->
              <div class="box box-warning">
                <div class="box-body">
                  
                    <div class="form-group">
                      <label>Query</label>
                      <textarea readonly class="form-control" name="query" placeholder="Query..."><?php if(!empty($contact->query)) { echo html_entity_decode($contact->query,ENT_QUOTES,'utf-8'); } ?></textarea>
                    </div>

                    <div class="form-group">
                      <label>Reply</label>
                      <textarea readonly class="form-control" name="reply" placeholder="Enter Reply to the query above"><?php if(!empty($contact->reply)) { echo html_entity_decode($contact->reply,ENT_QUOTES,'utf-8'); } ?></textarea>
                    </div>
                    
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!--/.col (right) -->
          </div>   <!-- /.row -->
        </section><!-- /.content -->
    
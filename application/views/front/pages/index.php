<style type="text/css" media="screen">
.book-box{
  text-align: left;
}  
</style>
<section class="main-book">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="book-heading">
          <h2><?php echo $page_header;?></h2>
        </div>
      </div>
      <div class="col-sm-12">
      <div class="col-sm-12">
          <div class="book-box">
              <p><?php echo isset($cmspage['content']) ? $cmspage['content'] : 'No Data Fount!'; ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="main-look">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="look">
          <h2>Look No Further. Get Started Today</h2>
          <a href="#" class="btn btn-primary join-batton">Join Now</a> </div>
      </div>
    </div>
  </div>
</section>
<?php if (($errors = validation_errors()) != '') { ?>
    <div class="alert alert-dismissable alert-danger  shake animated">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <strong>Error !</strong> <?php echo $errors; ?>
    </div>

<?php } ?>


<?php if (($flash = $this->session->flashdata('errors')) != '') { ?>
    <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <strong>Error !</strong> <?php echo $flash; ?>
    </div>

<?php } ?>

<?php if (($flash = $this->session->flashdata('success')) != '') { ?>
    <div class="alert alert-dismissable alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <strong> Success !</strong> <?php echo $flash; ?>
    </div>

<?php } ?>

<?php if (($flash = $this->session->flashdata('info')) != '') { ?>
    <div class=" alert-dismiss alert alert-dismissable alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <strong>Info !</strong> <?php echo $flash; ?>
    </div>

<?php } ?>


<script>
    $(function(){
        $(".alert-dismiss ").fadeTo(2000, 3000).slideUp(500, function(){
            $(".alert-dismiss ").alert('close');
        });
    });
</script>

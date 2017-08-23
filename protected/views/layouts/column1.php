<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>


        <div class="page-header position-relative">
            <?php if (! empty($this->pageHeader)) :?>
                        <h1>
                            <?php echo $this->pageHeader;?>
                            <!--<small>
                                <i class="icon-double-angle-right"></i>
                                based on widget boxes with 2 different styles
                            </small>-->
                        </h1>
            <?php endif;?>
        </div>

    <!--<div class="alert alert-error">
        <h3><i class="icon-exclamation-sign"></i> Warning!</h3>
        Best check yo self, you're not...
    </div>-->

    <div class="row-fluid">
        <div class="span12">
            <div id="content">
                <?php require_once('_flashes.php')?>

                <?php echo $content; ?>
            </div><!-- content -->
        </div>
    </div>

<?php $this->endContent(); ?>
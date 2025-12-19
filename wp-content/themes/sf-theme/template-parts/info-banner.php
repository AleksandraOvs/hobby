<?php
$info_banner = get_field('infobanner');
?>

<?php
if ($info_banner) {
?>
    <section class="info-banner">
        <div class="container">
            <div class="info-banner__inner">
                <?php echo $info_banner; ?>
            </div>
        </div>
    </section>
<?php } ?>
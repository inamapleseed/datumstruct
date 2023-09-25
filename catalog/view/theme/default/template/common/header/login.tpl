<div id="account" class="dropdown">
<?php if ($logged) { ?>
    <a href="#" title="<?=$text_account; ?>" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-user"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li>
            <a href="<?=$account; ?>">
                <?=$text_account; ?>
            </a>
        </li>
        <li>
            <a href="<?=$logout; ?>">
                <?=$text_logout; ?>
            </a>
        </li>
    </ul>
<?php }else { ?>
    <a href="#" data-toggle="dropdown">
        <i class="fa fa-user"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li>
            <a href="<?= $login; ?>" >
                <?=$text_login; ?>
            </a>
        </li>
        <li>
            <a href="<?= $register; ?>" >
                <?=$text_register; ?>
            </a>
        </li>
    </ul>
<?php } ?>
</div>
<?php $this->setSiteTitle("Administration"); ?>
<?php $this->start('body'); ?>
<h1>Administration</h1>


<table class="table table-striped table-condensed table-bordered table-hover">
    <thead>
        <th>Username</th>
        <th>Access Level</th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach($this->users as $user): ?>
            <tr>
                <td><?= $user->username ?></td>
                <td><?= $user->acl ?></td>
                <td class="text-center">
                    <a href="<?=APP_DOMAIN?>admindashboard/details/<?=$user->id?>" class="btn btn-info btn-xs">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php $this->end(); ?>
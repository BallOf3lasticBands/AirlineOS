<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';

$ag_query = "
    SELECT
        ag.id,
        ag.groupname,
        GROUP_CONCAT(
            COALESCE(
                NULLIF(CONCAT(u.firstname, ' ', u.lastname), ' '),
                u.email,
                CAST(u.id AS CHAR)
            )
            SEPARATOR ', '
        ) AS members
    FROM accessgroups ag
    LEFT JOIN accessgroups_members agm ON agm.accessgroup_id = ag.id
    LEFT JOIN users u ON u.id = agm.user_id
    GROUP BY ag.id, ag.groupname
    ORDER BY ag.id ASC
    LIMIT 200
";
$ag_result = mysqli_query($db_connect, $ag_query);

$page_title = 'Group List';
require_once __DIR__ . '/../../includes/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Group List</h2>
            <a href="add.php" class="btn btn-success">Add new group</a>
        </div>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php include __DIR__ . '/../../functions/message.php'; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"></span>
            </button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Group name</th>
                    <th scope="col">Members</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ag_result && $ag_result->num_rows > 0): ?>
                    <?php while ($accessgroup_data = mysqli_fetch_assoc($ag_result)): ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($accessgroup_data['id']) ?></th>
                            <td><?= htmlspecialchars($accessgroup_data['groupname']) ?></td>
                            <td><?= htmlspecialchars(!empty($accessgroup_data['members']) ? $accessgroup_data['members'] : 'No members assigned') ?></td>
                            <td>
                                <a href="edit.php?id=<?= urlencode($accessgroup_data['id']) ?>" class="btn btn-primary">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No data available!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($ag_result) { mysqli_free_result($ag_result); } ?>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php';

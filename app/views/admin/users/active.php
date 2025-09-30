<?php
// Check if the users directory exists, if not create it
if (!is_dir(dirname(__FILE__) . '/users')) {
    mkdir(dirname(__FILE__) . '/users', 0755, true);
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Active Users (Last 15 minutes)</h3>
                    <div class="card-tools">
                        <span class="badge badge-primary"><?php echo count($activeUsers); ?> Active</span>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-warning m-3">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Activity Tracking Issue</h5>
                            <?php if (strpos($error, 'Unknown column') !== false): ?>
                                <p>The user activity tracking feature is not yet set up. Please run the database migration to enable this feature.</p>
                                <div class="mt-2">
                                    <a href="<?php echo BASE_URL; ?>run_migration.php" class="btn btn-primary">
                                        <i class="fas fa-database"></i> Run Database Migration
                                    </a>
                                </div>
                            <?php else: ?>
                                <p>An error occurred: <?php echo htmlspecialchars($error); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php elseif (empty($activeUsers)): ?>
                        <div class="alert alert-info m-3">
                            <i class="icon fas fa-info"></i> No active users in the last 15 minutes.
                        </div>
                    <?php else: ?>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Last Activity</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activeUsers as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=edit&id=<?php echo $user['id']; ?>">
                                                <?php echo htmlspecialchars($user['username']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php 
                                                echo $user['role'] === 'admin' ? 'danger' : 
                                                    ($user['role'] === 'staff' ? 'warning' : 'info'); 
                                            ?>">
                                                <?php echo ucfirst($user['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php 
                                                $lastActivity = new DateTime($user['last_activity']);
                                                echo $lastActivity->format('M j, Y g:i A'); 
                                            ?>
                                            <small class="text-muted">
                                                (<?php echo timeAgo($user['last_activity']); ?>)
                                            </small>
                                        </td>
                                        <td><?php echo $user['ip_address'] ?? 'N/A'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<?php
// Helper function to display time ago
if (!function_exists('timeAgo')) {
    function timeAgo($time) {
        $time = strtotime($time);
        $time_difference = time() - $time;

        if ($time_difference < 1) { return 'just now'; }
        $condition = [
            12 * 30 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        ];

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;
            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }
    }
}
?>

<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: index.php");
    exit();
}
include "includes/db.php";

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ?;");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<html>
<head>
    <title>Dashboard- <?= $_SESSION['username']; ?></title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

<?php include "includes/header.php"; ?>
<main class="dashboard-container">

    <div class="dashboard-header">
        <h2>Your applications</h2>
        <button id="openAddModal" class="primary-btn">Add application</button><br>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <p id="msg" style="color: green; font-weight: bold;">
            <?= htmlspecialchars($_GET['msg']) ?>
        </p>
        <script>
            setTimeout(() => {
                const msg = document.getElementById('msg');
                if(msg) {
                    msg.style.display = 'none';
                }

                const url = new URL(window.location);
                url.searchParams.delete('msg');
                window.history.replaceState({}, '', url);
            }, 5000);
        </script>
    <?php endif; ?>
    <br>

    <?php if ($result->num_rows === 0): ?>
        <p>No applications yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Role title</th>
                    <th>Salary</th>
                    <th>Advert link</th>
                    <th>Response</th>
                    <th>Interview stage</th>
                    <th>Interview date</th>
                    <th>Offer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row=$result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Company"><?= htmlspecialchars($row['company']) ?></td>
                        <td data-label="Role Title"><?= htmlspecialchars($row['role_title']) ?></td>
                        <td data-label="Salary Rate"><?= htmlspecialchars($row['salary_rate']) ?></td>
                        <td data-label="Advert Link">
                            <a href="<?= htmlspecialchars($row['advert_link']); ?>" target="_blank">Link</a>
                        </td>
                        <td data-label="Response"><?= htmlspecialchars($row['response']) ?></td>
                        <td data-label="interview Stage"><?= htmlspecialchars($row['interview_stage']) ?></td>
                        <td data-label="Interview Date"><?= htmlspecialchars($row['interview_date']) ?></td>
                        <td data-label="Offer"><?= htmlspecialchars($row['offer']) ?></td>
                        <td data-label="Action">
                            <button 
                                class="edit-btn" data-id="<?= $row['id'] ?>"
                                data-company="<?= htmlspecialchars($row['company']) ?>"
                                data-role="<?= htmlspecialchars($row['role_title']) ?>"
                                data-salary="<?= htmlspecialchars($row['salary_rate']) ?>"
                                data-link="<?= htmlspecialchars($row['advert_link']) ?>"
                                data-response="<?= htmlspecialchars($row['response']) ?>"
                                data-stage="<?= htmlspecialchars($row['interview_stage']) ?>"
                                data-date="<?= htmlspecialchars($row['interview_date']) ?>"
                                data-offer="<?= htmlspecialchars($row['offer']) ?>">
                                Edit
                            </button>
                            <form action="actions/delete_application.php" method="POST" style="display:inline">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" onclick="return confirm('Delete this application?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<div id="addModal" class="modal-overlay">
    <div class="modal-box">
        <h3>Add application</h3>
        <form action="actions/add_application.php" method="POST">
            <label for="company">Company:</label>
            <input type="text" name="company" id="company" required>

            <label for="role">Role title:</label>
            <input type="text" name="role_title" id="role" required>

            <label for="salary">Salary/rate:</label>
            <input type="text" name="salary_rate" id="salary" required>

            <label for="link">Advert Link:</label>
            <input type="url" name="advert_link" id="link" required>

            <label for="response">Response:</label>
            <select name="response" id="response">
                <option value="Nothing Yet" selected>Nothing Yet</option>
                <option value="Positive Email/Call">Positive Email/Call</option>
                <option value="Rejection Email/Call">Rejection Email/Call</option>
            </select>

            <label for="stage">Interview stage:</label>
            <select name="interview_stage" id="tage">
                <option value="None" selected>None</option>
                <option value="Telephone">Telephone</option>
                <option value="Skill Assessment">Skill Assessment</option>
                <option value="Face-to-Face">Face-to-Face</option>
                <option value="Declined">Declined</option>
            </select>

            <label for="date">Interview date:</label>
            <input type="date" name="interview_date" id="date">

            <label for="offer">Offer:</label>
            <select name="offer" id="offer">
                <option value="YES">YES</option>
                <option value="NO" selected>NO</option>
            </select>

            <button type="submit">Add</button>
            <button type="button" onclick="closeAddModal()">Cancel</button> 
        </form>
    </div>
</div>

<div id="editModal" class="modal-overlay">
    <div class="modal-box">
        <h3>Edit application</h3>
        <form action="actions/edit_application.php" method="POST">
            <input type="hidden" name="id" id="edit-id">

            <label for="edit-company">Company:</label>
            <input type="text" name="company" id="edit-company" required>

            <label for="edit-role">Role title:</label>
            <input type="text" name="role_title" id="edit-role" required>

            <label for="edit-salary">Salary/rate:</label>
            <input type="text" name="salary_rate" id="edit-salary" required>

            <label for="edit-link">Advert Link:</label>
            <input type="url" name="advert_link" id="edit-link" required>

            <label for="edit-response">Response:</label>
            <select name="response" id="edit-response">
                <option value="Nothing Yet" selected>Nothing Yet</option>
                <option value="Positive Email/Call">Positive Email/Call</option>
                <option value="Rejection Email/Call">Rejection Email/Call</option>
            </select>

            <label for="edit-stage">Interview stage:</label>
            <select name="interview_stage" id="edit-stage">
                <option value="None" selected>None</option>
                <option value="Telephone">Telephone</option>
                <option value="Skill Assessment">Skill Assessment</option>
                <option value="Face-to-Face">Face-to-Face</option>
                <option value="Declined">Declined</option>
            </select>

            <label for="edit-date">Interview date:</label>
            <input type="date" name="interview_date" id="edit-date">

            <label for="edit-offer">Offer:</label>
            <select name="offer" id="edit-offer">
                <option value="YES">YES</option>
                <option value="NO" selected>NO</option>
            </select>

            <button type="submit">Update</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<?php include "includes/footer.php"; ?>

<script>
document.getElementById('openAddModal').addEventListener('click', () => {
    addModal.style.display = 'flex';
})

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
    clearInput();
}

document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-company').value = btn.dataset.company;
        document.getElementById('edit-role').value = btn.dataset.role;
        document.getElementById('edit-salary').value = btn.dataset.salary;
        document.getElementById('edit-link').value = btn.dataset.link;
        document.getElementById('edit-response').value = btn.dataset.response;
        document.getElementById('edit-stage').value = btn.dataset.stage;
        document.getElementById('edit-date').value = btn.dataset.date;
        document.getElementById('edit-offer').value = btn.dataset.offer;

        document.getElementById('editModal').style.display = 'flex';
    })
})

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function clearInput() {
    document.querySelector('#addModal form').reset();
}

</script>
</body>
</html>
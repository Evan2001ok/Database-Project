<!DOCTYPE html>
<html>
<head>
    <title>Term Project Demo</title>
        <script>
        function clearQuery() {
            document.getElementById('query').value = ''; // ???????
        }
    </script>
</head>
<body>
    <h1>Term Project Demo(yifan zhu)</h1>
    <form method="post" action="">
        <label for="query">Query Tables</label><br>
        <textarea name="query" id="query" rows="4" cols="50"><?php if (isset($_POST['query'])) echo htmlspecialchars(stripslashes($_POST['query'])); ?></textarea><br><br>
        <input type="submit" name="submit" value="Submit">
        <input type="button" value="Clear" onclick="clearQuery()"> <!-- Clear ?? -->
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $query = $_POST['query'];
	$query = stripslashes($query);

        // 检查是否包含 DROP 语句
        if (stripos($query, 'DROP') !== false) {
            echo "<p style='color: red;'>Error: DROP statements are not allowed.</p>";
        } else {
            // 连接到数据库
            $servername = "sysmysql8.auburn.edu";
            $username = "yzz0195";
            $password = "CQMYGzyf2001";
            $dbname = "yzz0195db";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // 检查连接
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // 执行查询
            $result = $conn->query($query);

            if ($result) {
                // 如果是 SELECT 查询，显示结果
                if (stripos($query, 'SELECT') === 0) {
                    echo "<table border='1'><tr>";
                    while ($field_info = $result->fetch_field()) {
                        echo "<th>{$field_info->name}</th>";
                    }
                    echo "</tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($row as $cell) {
                            echo "<td>" . htmlspecialchars($cell) . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                    echo "<p>Rows retrieved: " . $result->num_rows . "</p>";
                } else {
                    // 对于 INSERT、UPDATE、DELETE 等语句，显示操作结果
                    if ($conn->affected_rows > 0) {
                        echo "<p>Query executed successfully. Rows affected: " . $conn->affected_rows . "</p>";
                    } else {
                        echo "<p>Query executed successfully.</p>";
                    }
                }
            } else {
                // 显示 SQL 错误
                echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
            }

            $conn->close();
        }
    }
    ?>
</body>
</html>

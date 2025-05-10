<?php
require 'con.php';
include 'session.php';

// Redirect if already logged in
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit();
}

// Handle login form submission
if (isset($_POST["submit"])) {
    $username1 = $_POST["username1"];
    $password = $_POST["password"];

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($dbhandle, "SELECT * FROM user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username1);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            // Store session data
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["firstname"] = $row["firstname"];
            $_SESSION["role"] = $row["role"];

            // Redirect to home page
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Wrong Password');</script>";
        }
    } else {
        echo "<script>alert('User Not Registered');</script>";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Login Page</title>
    <style>
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(to bottom, #ffcccc, #ff9999);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,Scheduler 0,0.1);
            text-align: center;
            width: 400px;
            position: relative;
        }
        #canvas-container {
            width: 100%;
            height: 200px;
            position: relative;
            z-index: 1;
        }
        .title {
            color: #333;
            margin: 10px 0;
        }
        .input-group {
            margin: 15px 0;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .password-container {
            position: relative;
        }
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .forgot-password {
            text-align: right;
            margin: 10px 0;
            color: #ff6666;
            cursor: pointer;
        }
        .login-btn, .register-btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-btn {
            background: #ff6666;
            color: #fff;
            margin-bottom: 10px;
        }
        .login-btn:hover {
            background: #ff4d4d;
        }
        .register-btn {
            background: #ddd;
            color: #333;
        }
        .register-btn:hover {
            background: #ccc;
        }
        .or-text {
            margin: 10px 0;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="canvas-container"></div>
        <h2 class="title">APPLE QUALITY</h2>
        <h2 class="title">Application</h2>

        <form action="" method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="email" name="username1" placeholder="Enter email" autocomplete="off" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Enter password" autocomplete="new-password" required>
                    <span class="eye-icon" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>

            <p class="forgot-password">Forgot Password?</p>

            <button type="submit" name="submit" class="login-btn">Login</button>

            <p class="or-text">or</p>

            <button type="button" class="register-btn" onclick="location.href='register.php'">Register</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        // 3D Animation Setup
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, 400 / 200, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(400, 200);
        document.getElementById('canvas-container').appendChild(renderer.domElement);

        const appleGeometry = new THREE.SphereGeometry(1, 64, 64);
        const vertices = appleGeometry.attributes.position.array;
        for (let i = 0; i < vertices.length; i += 3) {
            const x = vertices[i];
            const y = vertices[i + 1];
            const z = vertices[i + 2];
            const radius = Math.sqrt(x * x + z * z);
            const verticalFactor = 1 - Math.abs(y) * 0.5;
            const middleBulge = Math.sin(Math.PI * (1 - Math.abs(y))) * 0.35;
            const scale = verticalFactor + middleBulge;
            vertices[i] = x * (1 + scale * 0.25);
            vertices[i + 2] = z * (1 + scale * 0.25);
            if (y < -0.7) {
                vertices[i + 1] = -0.75 + (y + 0.75) * 0.2;
            }
            if (y > 0.7 && radius < 0.4) {
                vertices[i + 1] -= 0.15;
            }
            vertices[i] += (Math.random() - 0.5) * 0.015;
            vertices[i + 1] += (Math.random() - 0.5) * 0.015;
            vertices[i + 2] += (Math.random() - 0.5) * 0.015;
        }
        appleGeometry.attributes.position.needsUpdate = true;
        appleGeometry.computeVertexNormals();

        const appleMaterial = new THREE.MeshPhongMaterial({
            color: 0xa11b0b,
            shininess: 80,
            specular: 0x888888,
            bumpScale: 0.05
        });
        const apple = new THREE.Mesh(appleGeometry, appleMaterial);
        scene.add(apple);

        const stemGeometry = new THREE.CylinderGeometry(0.05, 0.03, 0.6, 16);
        const stemVertices = stemGeometry.attributes.position.array;
        for (let i = 0; i < stemVertices.length; i += 3) {
            const y = stemVertices[i + 1];
            stemVertices[i] += Math.sin(y * Math.PI) * 0.07;
            stemVertices[i] += (Math.random() - 0.5) * 0.01;
        }
        stemGeometry.attributes.position.needsUpdate = true;
        const stemMaterial = new THREE.MeshPhongMaterial({ color: 0x4a3728, shininess: 20, specular: 0x333333 });
        const stem = new THREE.Mesh(stemGeometry, stemMaterial);
        stem.position.set(0, 1.2, 0);
        stem.rotation.x = Math.PI / 6;
        apple.add(stem);

        const leafGeometry = new THREE.PlaneGeometry(0.4, 0.7, 8, 8);
        const leafVertices = leafGeometry.attributes.position.array;
        for (let i = 0; i < leafVertices.length; i += 3) {
            const x = leafVertices[i];
            const y = leafVertices[i + 1];
            leafVertices[i + 1] += Math.sin(x * Math.PI) * 0.15;
            if (Math.abs(x) < 0.05) leafVertices[i + 2] += 0.02;
        }
        leafGeometry.attributes.position.needsUpdate = true;
        const leafMaterial = new THREE.MeshPhongMaterial({ color: 0x2e7d32, shininess: 30, side: THREE.DoubleSide, specular: 0x555555 });
        const leaf = new THREE.Mesh(leafGeometry, leafMaterial);
        leaf.position.set(0.1, 1.3, 0);
        leaf.rotation.z = Math.PI / 4;
        leaf.rotation.x = Math.PI / 3;
        apple.add(leaf);

        const pointLight = new THREE.PointLight(0xffffff, 1.5, 100);
        pointLight.position.set(5, 5, 5);
        scene.add(pointLight);
        const ambientLight = new THREE.AmbientLight(0x808080, 0.5);
        scene.add(ambientLight);
        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.4);
        directionalLight.position.set(-3, 2, 5);
        scene.add(directionalLight);

        camera.position.z = 3;

        window.addEventListener('resize', () => {
            renderer.setSize(400, 200);
            camera.aspect = 400 / 200;
            camera.updateProjectionMatrix();
        });

        let targetRotationX = 0;
        let targetRotationY = 0;
        let currentRotationX = 0;
        let currentRotationY = 0;
        const damping = 0.1;

        document.addEventListener('mousemove', (event) => {
            const mouseX = (event.clientX / window.innerWidth) * 2 - 1;
            const mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
            const mouseVector = new THREE.Vector3(mouseX, mouseY, 0.5);
            mouseVector.unproject(camera);
            const dir = mouseVector.sub(camera.position).normalize();
            const distance = -camera.position.z / dir.z;
            const mouseWorldPos = camera.position.clone().add(dir.multiplyScalar(distance));
            const applePos = new THREE.Vector3(0, 0, 0);
            targetRotationY = Math.atan2(mouseWorldPos.x - applePos.x, mouseWorldPos.z - applePos.z);
            targetRotationX = Math.atan2(mouseWorldPos.y - applePos.y, Math.sqrt(mouseWorldPos.x * mouseWorldPos.x + mouseWorldPos.z * mouseWorldPos.z));
            targetRotationX = Math.max(-Math.PI / 4, Math.min(Math.PI / 4, targetRotationX));
        });

        function animate() {
            requestAnimationFrame(animate);
            currentRotationX += (targetRotationX - currentRotationX) * damping;
            currentRotationY += (targetRotationY - currentRotationY) * damping;
            apple.rotation.y = currentRotationY;
            apple.rotation.x = currentRotationX;
            apple.rotation.z += 0.005;
            renderer.render(scene, camera);
        }
        animate();
    </script>
    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("fa-eye");
            toggleIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("fa-eye-slash");
            toggleIcon.classList.add("fa-eye");
        }
    }
    </script>
</body>
</html>
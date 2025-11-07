<DOCUMENT filename="register.php">
<?php
require 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = trim(htmlspecialchars($_POST["lastname"]));
    $firstname = trim(htmlspecialchars($_POST["firstname"]));
    $middlename = trim(htmlspecialchars($_POST["middlename"]));
    $gender = $_POST["gender"];
    $birthdate = $_POST["birthdate"];
    $contact_number = trim(htmlspecialchars($_POST["contact_number"]));
    $username = trim(htmlspecialchars($_POST["username"]));
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];

    $error = "";

    // Validate password strength
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Password must contain at least one number.";
    } elseif (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $error = "Password must contain at least one special character.";
    } elseif ($password !== $confirmpassword) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $dbhandle->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username has already been taken.";
            $stmt->close();
        } else {
            $stmt->close();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $dbhandle->prepare("INSERT INTO user (lastname, firstname, middlename, gender, birthdate, contact_no, username, password) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $lastname, $firstname, $middlename, $gender, $birthdate, $contact_number, $username, $passwordHash);

            if ($stmt->execute()) {
                $stmt->close();
                echo "<script>alert('Registered Successfully'); window.location.href = 'login.php';</script>";
                exit();
            } else {
                $error = "Error: Registration failed.";
                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Register - Apple Quality</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(to bottom, #ffcccc, #ff9999);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 15px;
            overflow-x: hidden;
        }
        .container {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
            text-align: center;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
        }
        #canvas-container {
            width: 100%;
            height: 220px;
            margin: 0 auto 15px;
            position: relative;
            border-radius: 10px;
        }
        .title {
            color: #333;
            margin: 8px 0;
            font-size: 1.6rem;
            font-weight: 600;
        }
        .subtitle {
            color: #ff6666;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }
        .input-group {
            margin: 12px 0;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-size: 0.95rem;
        }
        .input-group input, .input-group select {
            width: 100%;
            padding: 11px;
            border: 1.5px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
        }
        .input-group input:focus, .input-group select:focus {
            outline: none;
            border-color: #ff6666;
            box-shadow: 0 0 0 3px rgba(255, 102, 102, 0.1);
        }
        .password-container {
            position: relative;
        }
        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 1.1rem;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .full-width {
            grid-column: 1 / span 2;
        }
        .register-btn, .login-link-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: 0.3s;
        }
        .register-btn {
            background: #ff6666;
            color: #fff;
        }
        .register-btn:hover {
            background: #ff4d4d;
        }
        .login-link-btn {
            background: #eee;
            color: #333;
            text-decoration: none;
            display: inline-block;
        }
        .login-link-btn:hover {
            background: #ddd;
        }
        .or-text {
            margin: 12px 0;
            color: #777;
            font-size: 0.9rem;
        }
        .error {
            background: #ffe6e6;
            color: #c53030;
            padding: 10px;
            border-radius: 6px;
            margin: 15px 0;
            font-size: 0.9rem;
            border: 1px solid #feb2b2;
        }
        .password-requirements {
            font-size: 0.8rem;
            color: #666;
            text-align: left;
            margin-top: 5px;
            line-height: 1.4;
        }

        @media (max-width: 480px) {
            .grid {
                grid-template-columns: 1fr;
            }
            .full-width {
                grid-column: 1;
            }
            .container {
                padding: 20px;
            }
            #canvas-container {
                height: 180px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="canvas-container"></div>
        <h2 class="title">APPLE QUALITY</h2>
        <p class="subtitle">Create Your Account</p>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="grid">
                <div class="input-group">
                    <label>Last Name</label>
                    <input type="text" name="lastname" placeholder="Enter last name" required>
                </div>
                <div class="input-group">
                    <label>First Name</label>
                    <input type="text" name="firstname" placeholder="Enter first name" required>
                </div>
                <div class="input-group full-width">
                    <label>Middle Name</label>
                    <input type="text" name="middlename" placeholder="Enter middle name" required>
                </div>
                <div class="input-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select</option>
                        <option value="m">Male</option>
                        <option value="f">Female</option>
                        <option value="x">Prefer not to say</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Birthdate</label>
                    <input type="date" name="birthdate" required>
                </div>
                <div class="input-group full-width">
                    <label>Contact Number</label>
                    <input type="tel" name="contact_number" placeholder="Enter contact number" required>
                </div>
                <div class="input-group full-width">
                    <label>Username (Email)</label>
                    <input type="email" name="username" placeholder="Enter email" required>
                </div>
                <div class="input-group full-width">
                    <label>Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" placeholder="Enter password" required>
                        <span class="eye-icon" onclick="togglePassword('password', 'eye1')">
                            <i class="fas fa-eye" id="eye1"></i>
                        </span>
                    </div>
                    <div class="password-requirements">
                        8+ chars: uppercase, lowercase, number, special (!@#$%^&*)
                    </div>
                </div>
                <div class="input-group full-width">
                    <label>Confirm Password</label>
                    <div class="password-container">
                        <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm password" required>
                        <span class="eye-icon" onclick="togglePassword('confirmpassword', 'eye2')">
                            <i class="fas fa-eye" id="eye2"></i>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="register-btn">Create Account</button>

            <p class="or-text">or</p>

            <a href="login.php" class="login-link-btn">Already have an account? Sign In</a>
        </form>
    </div>

    <!-- 3D Apple Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        const canvasContainer = document.getElementById('canvas-container');
        const width = canvasContainer.clientWidth;
        const height = canvasContainer.clientHeight;

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setSize(width, height);
        renderer.setClearColor(0x000000, 0);
        canvasContainer.appendChild(renderer.domElement);

        // Apple Geometry
        const appleGeometry = new THREE.SphereGeometry(1, 64, 64);
        const vertices = appleGeometry.attributes.position.array;
        for (let i = 0; i < vertices.length; i += 3) {
            const x = vertices[i], y = vertices[i + 1], z = vertices[i + 2];
            const radius = Math.sqrt(x * x + z * z);
            const verticalFactor = 1 - Math.abs(y) * 0.5;
            const middleBulge = Math.sin(Math.PI * (1 - Math.abs(y))) * 0.35;
            const scale = verticalFactor + middleBulge;
            vertices[i] = x * (1 + scale * 0.25);
            vertices[i + 2] = z * (1 + scale * 0.25);
            if (y < -0.7) vertices[i + 1] = -0.75 + (y + 0.75) * 0.2;
            if (y > 0.7 && radius < 0.4) vertices[i + 1] -= 0.15;
        }
        appleGeometry.attributes.position.needsUpdate = true;
        appleGeometry.computeVertexNormals();

        const appleMaterial = new THREE.MeshPhongMaterial({
            color: 0xa11b0b, shininess: 80, specular: 0x888888
        });
        const apple = new THREE.Mesh(appleGeometry, appleMaterial);
        scene.add(apple);

        // Stem
        const stemGeometry = new THREE.CylinderGeometry(0.05, 0.03, 0.6, 16);
        const stem = new THREE.Mesh(stemGeometry, new THREE.MeshPhongMaterial({ color: 0x4a3728 }));
        stem.position.set(0, 1.2, 0);
        stem.rotation.x = Math.PI / 6;
        apple.add(stem);

        // Leaf
        const leafGeometry = new THREE.PlaneGeometry(0.4, 0.7, 8, 8);
        const leafVertices = leafGeometry.attributes.position.array;
        for (let i = 0; i < leafVertices.length; i += 3) {
            const x = leafVertices[i];
            leafVertices[i + 1] += Math.sin(x * Math.PI) * 0.15;
        }
        leafGeometry.attributes.position.needsUpdate = true;
        const leaf = new THREE.Mesh(leafGeometry, new THREE.MeshPhongMaterial({ color: 0x2e7d32, side: THREE.DoubleSide }));
        leaf.position.set(0.1, 1.3, 0);
        leaf.rotation.z = Math.PI / 4;
        leaf.rotation.x = Math.PI / 3;
        apple.add(leaf);

        // Fixed Lights
        const pointLight = new THREE.PointLight(0xffffff, 1.5, 100);
        pointLight.position.set(5, 5, 5);
        scene.add(pointLight);

        const ambientLight = new THREE.AmbientLight(0x808080, 0.5);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.4);
        directionalLight.position.set(-3, 2, 5);
        scene.add(directionalLight);

        camera.position.z = 3;

        // Rotation Animation
        function animate() {
            requestAnimationFrame(animate);
            apple.rotation.y += 0.01;
            apple.rotation.x = Math.sin(Date.now() * 0.001) * 0.2;
            renderer.render(scene, camera);
        }
        animate();

        // Password toggle
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        // Resize handler
        window.addEventListener('resize', () => {
            const width = canvasContainer.clientWidth;
            const height = canvasContainer.clientHeight;
            renderer.setSize(width, height);
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
        });
    </script>
</body>
</html>
</DOCUMENT>
<?php
date_default_timezone_set('Asia/Manila');
require 'conn.php';
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
        $storedPassword = $row["password"];
        
        // Standard password verification
        if (password_verify($password, $storedPassword)) {
            // Store session data
            $_SESSION["id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["firstname"] = $row["firstname"];
            $_SESSION["role"] = $row["role"];

            // Log successful login
            $username = $row["username"];
            $date = date('Y-m-d');
            $time_in = date('H:i:s');
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $log_stmt = mysqli_prepare($dbhandle, "INSERT INTO user_log (username, date, time_in, ip_address) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($log_stmt, "ssss", $username, $date, $time_in, $ip_address);
            mysqli_stmt_execute($log_stmt);
            mysqli_stmt_close($log_stmt);

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
    <title>Login - Apple Quality Application</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'apple-red': {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                            900: '#7f1d1d',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        .gradient-bg {
            background: linear-gradient(135deg, #ffcccc 0%, #ff9999 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
            border-color: #f87171;
        }
        
        .password-toggle {
            transition: all 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #ef4444;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl card-shadow overflow-hidden">
        <!-- Header with 3D Apple Animation -->
        <div class="relative bg-gradient-to-r from-apple-red-500 to-apple-red-600 p-6">
            <div id="canvas-container" class="h-48 w-full flex items-center justify-center"></div>
            <div class="text-center text-white mt-2">
                <h1 class="text-2xl font-bold">APPLE QUALITY</h1>
                <p class="text-apple-red-100 mt-1">Application</p>
            </div>
        </div>
        
        <!-- Login Form -->
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Sign in to your account</h2>
            
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="username1" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <input type="email" name="username1" id="username1" placeholder="Enter your email" autocomplete="off" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Enter your password" autocomplete="new-password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus focus:outline-none transition-colors pr-10">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <button type="button" onclick="togglePasswordVisibility()" class="password-toggle">
                                <i class="fas fa-eye text-gray-400" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="h-4 w-4 text-apple-red-600 border-gray-300 rounded focus:ring-apple-red-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-medium text-apple-red-600 hover:text-apple-red-500 transition-colors">Forgot password?</a>
                </div>
                
                <button type="submit" name="submit" class="w-full bg-apple-red-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-apple-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-apple-red-500">
                    Sign In
                </button>
                
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="register.php" class="font-medium text-apple-red-600 hover:text-apple-red-500 transition-colors">Create one now</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- 3D Animation Script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        // Password Toggle Function
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
</body>
</html>
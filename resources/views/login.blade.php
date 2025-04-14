<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Moodle</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 10px;
            font-size: 24px;
            color: #333;
        }

        /* Adicionando estilo para o subtítulo */
        .subtitulo {
            font-size: 12px;
            color: #777;
            margin-bottom: 20px;
        }

        .input-group {
            position: relative;
            width: 100%;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #777;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #09a7ab;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #077a7a;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .register-link {
            margin-top: 10px;
        }

        .register-link a {
            color: #09a7ab;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>ON FLAIR</h1>
        <!-- Novo subtítulo abaixo do título -->

        <form id="loginForm">
            @csrf
            <input type="text" id="username" name="username" placeholder="Usuário" required>

            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Senha" required>
                <span class="eye-icon" onclick="togglePassword()" id="eyeIcon">
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path d="M12 5C7 5 2.7 8.1 1 12c1.7 3.9 6 7 11 7s9.3-3.1 11-7c-1.7-3.9-6-7-11-7Zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"/></svg>
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="display: none;"><path d="M3.1 2.2A1 1 0 0 0 1.9 3.4L5 6.5C2.9 8.2 1.4 10 1 12c1.7 3.9 6 7 11 7 2 0 3.9-.4 5.6-1.2l2.4 2.4a1 1 0 0 0 1.4-1.4l-18-18ZM12 17a5 5 0 0 1-5-5c0-.8.2-1.5.5-2.1l6.6 6.6c-.6.3-1.3.5-2.1.5Zm4.5-2.1-6.4-6.4c.6-.3 1.3-.5 1.9-.5a5 5 0 0 1 5 5c0 .7-.2 1.4-.5 1.9Zm3.9-2.9c-.3.6-.7 1.2-1.1 1.7a7 7 0 0 0-9-9 14 14 0 0 1 6.1 1.7c1.8 1 3.3 2.5 4.6 4.3.3.5.5 1 .7 1.3l-.3.7Z"/></svg>
                </span>
            </div>

            <button type="submit">Entrar</button>
        </form>
        <p class="error-message" id="errorMessage"></p>
        <p class="register-link" id="registerLink">
           Efetue seu cadastro <a href="{{ url('/users/cadastro') }}">CLICANDO AQUI</a>
        </p>
    </div>

    <script>
        function togglePassword() {
            let passwordField = document.getElementById("password");
            let eyeOpen = document.getElementById("eyeOpen");
            let eyeClosed = document.getElementById("eyeClosed");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeOpen.style.display = "none";
                eyeClosed.style.display = "inline";
            } else {
                passwordField.type = "password";
                eyeOpen.style.display = "inline";
                eyeClosed.style.display = "none";
            }
        }

        $('#loginForm').submit(function(e) {
            e.preventDefault();

            let username = $('#username').val();
            let password = $('#password').val();

            $.ajax({
                url: "{{ url('/users/login') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    email: username,
                    password: password
                },
                xhrFields: {
                    withCredentials: true // <- ESSENCIAL para manter a sessão!
                },
                success: function(response) {
                    if (response.redirect_url) {
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(xhr, status, error) {
                    let errorMsg = "Erro ao tentar fazer login.";
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    $("#errorMessage").text(errorMsg);
                }
            });
        });
    </script>
</body>
</html>

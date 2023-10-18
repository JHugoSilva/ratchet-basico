<?php 
session_start();

ob_start();

if (!isset($_SESSION['usuario_id']) OR !isset($_SESSION['nome'])) {
    $_SESSION['msg'] = "<p style='color:#F00;'>Erro: Usuário ou senha inválida</p>";
    header("Location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Celke - WebSocket</title>
</head>
<body>
    <h2>Chat</h2>
    <a href="sair.php">Sair</a><br><br>
    <p>Bem-Vindo: <span id="nome-usuario"><?= $_SESSION['usuario'];?></span></p>
    <label for="">Nova Mensagem:</label>
    <input type="hidden" name="usuario_id" id="usuario_id" value="<?= $_SESSION['usuario_id'];?>">
    <input type="text" name="mensagem" id="mensagem" placeholder="Digite a mensagem..."><br><br>
    <input type="button" onclick="enviar()" value="Enviar"><br><br>
    <span id="mensagem-chat"></span>
    <script>
        const mensagemChat = document.getElementById('mensagem-chat')

        const ws = new WebSocket('ws://localhost:8080')

        ws.onopen = (e) => {
            console.log('Conectado!')
        }

        ws.onmessage = (mensagemRecebida) => {
            let resultado = JSON.parse(mensagemRecebida.data)

            mensagemChat.insertAdjacentHTML('beforeend', `${resultado.mensagem} <br>`)
        }

        const enviar = () => {

            let mensagem = document.getElementById('mensagem')

            let nomeUsuario = document.getElementById('nome-usuario').textContent

            let usuarioId = document.getElementById('usuario_id').value

            if (usuarioId === "") {
                alert("Erro: Necessário realizar o login para acessar a página")
                window.location.href = "index.php"
                return
            }

            let dados = {
                mensagem: `${nomeUsuario} : ${mensagem.value}`,
                usuario_id: usuarioId,
            }

            ws.send(JSON.stringify(dados))

            mensagemChat.insertAdjacentHTML('beforeend', `${nomeUsuario} : ${mensagem.value} <br>`)
            mensagem.value = ''
        }
    </script>
</body>
</html>
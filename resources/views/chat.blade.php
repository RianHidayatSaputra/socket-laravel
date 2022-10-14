<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Socket Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  </head>
</head>
<body>

    <form onsubmit="return enterName()">
        <input type="text" id="name" placeholder="Enter name..."> 
        <input type="submit"> 
    </form>

    <ul id="users"></ul>

    <form onsubmit="return sendMessage()">
        <input type="text" id="message" placeholder="Enter message...">
        <input type="submit">
    </form>

    <ul id="messages"></ul>








    {{-- <div class="container mt-5">
        <div class="container mt-4 w-75" style="height: auto;" id="loginContainer">
            <h3 class="text-center">OPEN FORUM</h3>
            <form action="#" id="loginForm">
                <input type="text" id="nameUser" class="w-100 form-control" placeholder="Your Name" required>
                <p id="error" class="text-danger mt-1 mb-3"></p> 
                <input type="submit" class="btn btn-danger w-100" value="Join Now" id="loginButton">
            </form>
        </div>
        <div class="row justify-content-center" id="chatContainer">
            <div class="border w-50 d-flex justify-content-center">
                <div >
                    <div id="box-pesan">

                    </div>
                    <div class="col-md-12">
                        <input type="text" id="chatInput" >  
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <p>Yang Online:</p>
                <div id="online">

                </div>
            </div>
        </div> --}}
        {{-- pribadi --}}
        {{-- <div class="row justify-content-center" id="personalChatContainer">
            <div class="border w-50 d-flex justify-content-center">
                <div >
                    <div id="box-pesan-personal">

                    </div>
                    <div class="col-md-12">
                        <input type="text" id="chatInputPersonal" >  
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    
    <script src="https://cdn.socket.io/4.5.0/socket.io.min.js" integrity="sha384-7EyYLQZgWBi67fBtVxw60/OWl1kjsfrPFcaU0pp0nAh+i8FD068QogUvg85Ewy1k" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <script>

        const ip_address = '127.0.0.1'
        const socket_port = '2000'
        const socket = io(`${ip_address}:${socket_port}`)

        var receiver = ''
        var sender = ''

        function enterName() {
            var name = document.getElementById('name').value

            socket.emit('userConnectedToServer', name)

            sender = name

            return false
        }

        socket.on('userConnectedToClient', (username) => {
            var html = ''
            html += `<li><button onclick='onUserSelected(this.innerHTML)'>${username}</li>`

            document.getElementById('users').innerHTML += html
        })

        function onUserSelected(username) {
            receiver = username

            $.ajax({
                url: "http://localhost:2000/get_messages",
                method: "POST",
                data: {
                    sender,
                    receiver
                },

                success: function (result) {
                    var messages = JSON.parse(result)
                    var html = ''

                    for (var a = 0; a < messages.length; a++){
                        html += `<li>${messages[a].sender} : ${messages[a].message}</li>`
                    }
                    $('#messages').html(html);
                }
            })
        }

        function sendMessage() {
            var message = document.getElementById('message').value

            socket.emit('sendMessageToServer', {
                sender,
                receiver,
                message
            })

            var html = ''
            html += `<li>You : ${message}</li>`

            document.getElementById('messages').innerHTML += html

            return false
        }

        socket.on('sendMessageToClient', (data) => {
            var html = ''
            html += `<li>${data.sender} : ${data.message}</li>`

            document.getElementById('messages').innerHTML += html
        })




































        // const chatInput = document.querySelector('#chatInput')
        // const chatInputPersonal = document.querySelector('#chatInputPersonal')
        // const boxPesan = document.querySelector('#box-pesan')
        // const boxPesanPersonal = document.querySelector('#box-pesan-personal')
        // const loginContainer = document.getElementById('loginContainer');
        // const loginButton = document.getElementById('loginButton');
        // const chatContainer = document.getElementById('chatContainer');
        // const personalChatContainer = document.getElementById('personalChatContainer');
        // const userOnline = document.getElementById('online');
        // const nameUser = document.getElementById('nameUser')
        // const error = document.getElementById('error')
        // let idMauDiChat = ''

        // //before loginButton click
        // chatContainer.style.display = 'none';
        // personalChatContainer.style.display = 'none';
        // // loginButton.style.display = 'none';

        // nameUser.addEventListener('keyup', (e) => {
        //     e.preventDefault()
        //     loginButton.style.display = 'block'
        // });

        // loginButton.addEventListener('click', (e) => {
        //     e.preventDefault()
        //     const p = document.createElement('p')

        //     if (nameUser.value == '') {
        //         error.textContent = 'Must input your name!'
        //         nameUser.style.borderColor = 'red'
        //         return false;
        //     }
        //     //after loginButton click
        //     chatContainer.style.display = 'block'
        //     loginContainer.style.display = 'none'

        //     p.textContent = 'anda'

        //     userOnline.appendChild(p)

        //     socket.emit('userOnline', {
        //         id: socket.id,
        //         name: nameUser.value
        //     })
        // });

        // chatInput.addEventListener('keypress', function (e) {
        //     const p = document.createElement('p')
        //     const message = chatInput.value

        //     if (e.which === 13 && !e.shiftKey) {
        //         socket.emit('chatToServer', message)
        //         chatInput.value = ''
                
        //         p.style.paddingLeft = '85%'
        //         p.textContent = message

        //         boxPesan.appendChild(p)
        //     }
        // })
        
        // chatInputPersonal.addEventListener('keypress', function (e) {
        //     const p = document.createElement('p')
        //     const message = chatInputPersonal.value

        //     if (e.which === 13 && !e.shiftKey) {
        //         socket.emit('personalChat', {msg: message})

        //         chatInputPersonal.value = ''
                
        //         p.style.paddingLeft = '85%'
        //         p.textContent = message

        //         boxPesanPersonal.appendChild(p)
        //     }
        // })

        // socket.on('persChat', (data) => {
        //     const p = document.createElement('p')
        //     if (data.msg == '') {
        //         p.textContent = 'tess'
        //         boxPesanPersonal.appendChild(p)
        //     }

        //     p.textContent = data.msg
        //     boxPesanPersonal.appendChild(p)
        // })
        
        // socket.on('chatToClient', (message) => {
        //     const p = document.createElement('p')

        //     p.textContent = message

        //     boxPesan.appendChild(p)
        // })

        // socket.on('showUser', (user) => {
        //     const p = document.createElement('p')
        //     idMauDiChat = user.id
        //     p.textContent = user.id
        //     p.style.cursor = 'pointer'
        //     userOnline.appendChild(p)

        //     p.addEventListener('click', function () {
        //         personalChatContainer.style.display = 'block'

        //         socket.emit('personalChat', idMauDiChat, chatInputPersonal.value)
        //     })
        // })

    </script>
</body>
</html>
const express = require('express')

const app = express()

const server = require('http').createServer(app)

const io = require('socket.io')(server, {
    cors: {
        origin: '*'
    }
})

const bodyParser = require('body-parser')

app.use(bodyParser.urlencoded())

const mysql = require('mysql')

const connection = mysql.createConnection({
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "chat"
})

connection.connect((error) => {
    console.log(error)
})

app.use((req, res, next) => {
    res.setHeader("Access-Control-Allow-Origin", "*")
    next()
})

app.post('/get_messages', (req, res) => {
    connection.query("SELECT * FROM messages WHERE (sender = '" + req.body.sender + "' AND receiver = '" + req.body.receiver + "') OR (sender = '" + req.body.receiver + "' AND receiver = '" + req.body.sender + "')", function (error, messages) {
        if (error) {
            console.log(error)
        }

        res.end(JSON.stringify(messages, null, 2))
    })
})

var users = []

io.on('connection', (socket) => {
    console.log('User Connected ', socket.id)

    socket.on('userConnectedToServer', (username) => {
        users[username] = socket.id

        io.emit('userConnectedToClient', username)
    })

    socket.on('sendMessageToServer', (data) => {
        var socketId = users[data.receiver]

        io.to(socketId).emit('sendMessageToClient', data)

        connection.query("INSERT INTO messages (message,sender,receiver) VALUES ('" + data.message + "','" + data.sender + "','" + data.receiver + "')", (error, result) => {

        })
    })









    // socket.on('personalChat', (personal) => {
    //     io.to(personal).emit('persChat', { msg: personal.msg });
    // })

    // socket.on('userOnline', (user) => {
    //     socket.broadcast.emit('showUser', user)
    // })

    // socket.on('chatToServer', (message) => {
    //     socket.broadcast.emit('chatToClient', message)
    // })

    // socket.on('disconnect', (socket) => {
    //     console.log('Server Disconnect!')
    // })
})

server.listen(2000, () => console.log('Server listening in the port 2000...'))
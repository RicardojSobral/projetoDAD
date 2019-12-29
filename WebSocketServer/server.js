/* jshint esversion: 6 */

var app = require("http").createServer();

 /*var app = require('http').createServer(function(req,res){
  res.setHeader('Access-Control-Allow-Origin', 'http://dad.projeto.test');
  res.setHeader('Access-Control-Request-Method', '*');
  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
  res.setHeader('Access-Control-Allow-Credentials', true);
  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
      res.writeHead(200);
      res.end();
      return;
  }
 });*/


var io = require("socket.io")(app);

var LoggedUsers = require("./loggedusers.js");

app.listen(8080, function() {
    console.log("listening on *:8080");
});

let loggedUsers = new LoggedUsers();

io.on("connection", function(socket) {
    console.log("client has connected (socket ID = " + socket.id + ")");
    //console.log(loggedUsers);

    socket.on('disconnect', (reason) => {
        if (reason === 'io server disconnect') {
          socket.connect();
        }
        console.log('Disconnected');
        loggedUsers.removeUserInfoBySocketID(socket.id);
        //console.log(loggedUsers);
    });

    socket.on("user_enter", function(user) {
        if (user) {
            loggedUsers.addUserInfo(user, socket.id);
            console.log('User ' + user.id + (' added to loggedUsers'));
            //console.log(loggedUsers);
        }
    });

    socket.on("user_exit", function(user) {
        if (user) {
            loggedUsers.removeUserInfoByID(user.id);
            console.log('User ' + user.id + (' removed from loggedUsers'));
            //console.log(loggedUsers);
        }
    });

    socket.on("user_changed_income", function(msg, destUser) {
        let userInfo = loggedUsers.userInfoByID(destUser.id);
        let socket_id = userInfo !== undefined ? userInfo.socketID : null;
        if (socket_id === null) {
          //socket.emit("privateMessage_unavailable", destUser); //enviar mail
        } else {
          io.to(socket_id).emit("user_changed_income", msg);
        }
    });

    socket.on("user_changed_transfer", function(msg, sourceUser, destUser) {
        console.log(sourceUser);
        let userInfo = loggedUsers.userInfoByID(destUser.id);
        let socket_id = userInfo !== undefined ? userInfo.socketID : null;
        if (socket_id === null) {
            //socket.emit("privateMessage_unavailable", destUser); //enviar mail
        } else {
            io.to(socket_id).emit("user_changed_transfer", msg, sourceUser); 
        }
    });

});
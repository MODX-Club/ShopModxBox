import React, {Component} from 'react';
import ReactDOM from 'react-dom';
import PropTypes from "prop-types";

import CastConnected from 'material-ui-icons/CastConnected';
import Loop from 'material-ui-icons/Loop';
import IconButton from 'material-ui/IconButton';

import {white, red500, yellow500, blue500} from 'material-ui/styles/colors';

import * as proxyActions from 'modules/Redux/actions/proxyActions';
import * as userActions from 'modules/Redux/actions/userActions';
import * as documentActions from 'modules/Redux/actions/documentActions';
import {connect} from 'react-redux';
import {bindActionCreators} from 'redux';

// import { CONNECTOR_URL } from '../const';

// import config from '../../../../../config/config.js';



const defaultProps = {
  host: typeof window !== "undefined" && 'ws://' + window.location.host + '/api/' || '',
};

class WsProxy extends Component {

  static contextTypes = {
    request: PropTypes.func.isRequired,
    remoteQuery: PropTypes.func.isRequired,
  };

  state = {
    LogoutInProgress: false,
  };

  ConnectionsInProgress = false;
  ConnectionsBlocked = false;
  ConnectionsAttempts = 0;
  LastConnectAttempt = null;

  constructor(props) {

    super(props);
  }


  OnConnected() {
    // informer.success('Соединение установлено');
    // this.state.connected = true;
    // this.UserMenu.forceUpdate();
  }

  OnConnectionOpen(socket) {
  }

  // OnConnectionClose(socket) {
  //   informer.failure("Соединение разорвано");
  //
  //   // $('[data-chat-form="chat-connect-form"]')
  //   //         .show();
  //   //
  //   //     $('[data-chat-form="chat-message-form"]')
  //   //         .hide();
  //
  // }

  SocketOnMessage(event) { 

    // alert('SocketOnMessage');
    // return;

    var incomingMessage = event.data;


    var response;

    try {
      response = JSON.parse(incomingMessage);

      var type = response.type || (response.original_message ? response.original_message.type : "");

      switch (type) {
        case 'hello':
          // socket.send(JSON.stringify({
          //   type: 'introdution',
          //   channel_id: this.props.channel_id,
          //   id: 1,
          //   name: 'test'
          // }));
          // this.OnConnected();
          break;


          /*
          * Этот запрос обрабатывается поисковой строчкой и прочими компонентами
          * */
        case 'form':
        case 'search':
        case 'find_user':
        case 'signup':
        case 'chat_message':
          // socket.send(JSON.stringify({
          //   type: 'introdution',
          //   channel_id: this.props.channel_id,
          //   id: 1,
          //   name: 'test'
          // }));
          // this.OnConnected();
          break;

        case 'signout':

          if(response.success){
            this.props.userActions.logoutComplete();
          }
          else{
            this.props.userActions.logoutFailed();
          }

          this.setState({
            LogoutInProgress: false,
          });
          break;


        case 'signin':
          // response.readed = true;

          break;

        case 'user/get_own_data':
          // socket.send(JSON.stringify({
          //   type: 'introdution',
          //   channel_id: this.props.channel_id,
          //   id: 1,
          //   name: 'test'
          // }));
          // this.OnConnected();

          if(!response.success || !response.object.id){
            this.props.userActions.GetOwnDataFailed(response);
          }
          else{
            this.props.userActions.GetOwnDataSuccess(response.object);
          }

          break;

        case 'load_document':

          response.readed = true;


          this.props.documentActions.DocumentLoaded(response.resource);
          break;

        case 'active_users_list':

          response.readed = true;
          //


          this.props.userActions.UpdateUsersList(response.users);
          break;

        case 'message':


        //   var cls = '';
        //
        //   var d = new Date();
        //   var date = d.toLocaleTimeString('en-US', {hour12: false});
        //
        //   // var date = new Date().toLocaleFormat( "%H:%M:%S" );
        //
        //   var my_message = response.sender.id == id;
        //
        //


        //
        //   // var div = $('<div class="message '+ cls +'">'+ response.text +'</div>');
        //
        //   var code = '<div class="chat-room__message-box ' + (my_message ? "chat-room__message-box--my-message" : "") + '">';
        //
        //   if (response.sender.photo) {
        //     code += '<a href="javascript:;" class="chat-room__user-image-box">\
        //         <img src="' + response.sender.photo + '" alt="" style="border-radius: 50%;">\
        //     </a>';
        //   }
        //
        //   code += '<div class="chat-room__message">\
        //         <a href="javascript:;" class="chat-room__user-link">' + response.sender.name + (response.sender.guest ? ' <span class="text-danger">(Гость)</span>' : '') + '</a>\
        //         <div class="chat-room__text-message">\
        //             ' + response.text + '\
        //             <span class="chat-room__message-time">' + date + '</span>\
        //         </div>\
        //     </div>\
        // </div>';
        //
        //   var div = $(code);
        //
        //
        //   var body_item = $('[data-chat-item="body"]');
        //
        //   body_item.append(div)
        //   // $('.chat-room__window-inside')
        //     .animate({scrollTop: body_item.height() + body_item.scrollTop()}, "slow");
          break;

        case 'introdution_confirm':

          // // 
          // var id = response.id;
          //
          // // $('[data-chat-form="chat-connect-form"]')
          // //     .hide();
          // //
          // // $('[data-chat-form="chat-message-form"]')
          // //     .show();
          // //
          // // $('[data-chat-element="intro"]').hide();
          //
          // socket.send(JSON.stringify({
          //   type: 'joined',
          //   client_id: id,
          //   channel_id: this.props.channel_id,
          //   text: "Ура! Я подключился))"
          // }));
          //
          // //
          // this.OnLogin();

          break;

        case 'error':
          alert(response.text || "Ошибка выполнения запроса");
          break;

        case 'joined':
          // var body_item = $('[data-chat-item="body"]');
          //
          // var div = $('<div class="chat-room__message-box chat-room__message-box--notification">\
          //       <div class="chat-room__notification-box">\
          //           <a href="javascript:;" class="chat-room__notification-link">' + response.text + '</a> подключился к чату\
          //       </div>');
          //
          //
          // body_item.append(div)
          //   .animate({scrollTop: body_item.height() + body_item.scrollTop()}, "slow");
          //
          //
          // var users_html = '';
          //
          //
          // // Обновляем список пользователей
          // for (var i in response.users) {
          //   var user = response.users[i];
          //
          //
          //   var photo = user.photo;
          //
          //   users_html += '<a href="javascript:;" class="settings-chat__user-link"\
          //   data-toggle="tooltip" data-placement="bottom" title="' + user.name.replace('"', '\"') + '">\
          //       <img src="' + photo + '" alt=""' + (user.guest ? ' style="filter: grayscale(100%);"' : '') + '>\
          //   </a>';
          //
          //
          // }
          //
          // $('[data-chat-item="users"]').html(users_html);


          break;

        default:
          alert("Не известный тип сообщения");

      }

      this.props.proxyActions.receive_message(response);
    }
    catch (e) {
      console.error('Error!');
      console.error(incomingMessage);
      console.error(e);
    }

  };


  log_connection_state() {
    this.props.proxyActions.SetConnectionState((this.socket && this.socket.readyState == this.socket.OPEN) === true);
  }

  onopen() {

    // this.props.proxyActions.send_message(JSON.stringify({"type":"message"}), this);

    // this.props.userActions.addNotification("Установлено постоянное соединение");


    this.log_connection_state();

    this.props.proxyActions.connected();
    //
    this.props.userActions.GetOwnData();
  }

  onclose() {
    // this.props.proxyActions.disconnected();
    this.props.userActions.addNotification("Соединение разорвано");
    // this.props.proxyActions.SetConnectionState(this.socket && this.socket.OPEN);

    this.log_connection_state();
  }

  connect() {

    if(typeof window == "undefined"){
      return;
    }

    if(window.ws && window.ws.readyState !== window.ws.CLOSED){
      return;
    }

    if (
      (
        !this.socket
        || !(this.socket.readyState == this.socket.OPEN)
      )
      && this.props.connection_allowed
      && !this.props.connection_in_progress
      // && typeof window === "object"
    ) {


      if(this.ConnectionsInProgress){
        return;
      }

      this.ConnectionsInProgress = true;

      this.ConnectionsAttempts++;

      if(this.ConnectionsBlocked){
        return false;
      }

      if(this.ConnectionsAttempts > 5){



        this.ConnectionsBlocked = true;
        return;
      }

      if(this.LastConnectAttempt && this.LastConnectAttempt > (new Date().getTime() - 1000)){

        console.error("Слишком частые попытки соединения");
        this.ConnectionsBlocked = true;
        return;
      }


      this.socket = new WebSocket(this.props.host);

      // this.socket.onmessage = this.SocketOnMessage.bind(this, this.socket);

      // this.socket.onopen = this.props.proxyActions.connected;
      this.socket.onopen = this.onopen.bind(this);

      // this.socket.onclose = this.onclose.bind(this);
      this.socket.onclose = this.onclose.bind(this);
      //
      // this.socket.onmessage = this.props.proxyActions.receive_message;
      this.socket.onmessage = this.SocketOnMessage.bind(this);

      window.ws = this.socket;
    }



    // this.props.proxyActions.SetConnectionState(this.socket && this.socket.OPEN);
  }



  disconnect() {
    // this.props.proxyActions.RequestConnection();
    if (
        this.socket
        && (this.socket.readyState == this.socket.OPEN)
    ) {

      this.socket.close();
    }
  }

  RequestConnection() {
    this.props.proxyActions.RequestConnection();
  }

  DisallowConnection() {
    this.props.proxyActions.DisallowConnection();
  }

  AllowConnection() {
    this.props.proxyActions.AllowConnection();
  }

  SendMessage(message){


    if(this.props.connected){

      if(!message.ts){
        console.error("WsProxy. Не была получена метка сообщения");
      }
      else{

        this.socket.send(JSON.stringify(message));
        this.props.proxyActions.MessageSended(message);
      }

    }
  }

  load_document(url){

    // this.props.proxy.


    // // alert(url);
    //
    // this.props.proxyActions.SendMessage({
    //   type: "load_document",
    //   url: url,
    // });

    fetch(url,{
      credentials: 'same-origin',
      method: "POST",
      // body: body,
    })
      .then(function (response) {
        return response.json()
      })
      .then(function (data) {
        // self.setState({orders: data.object});

        if(data.success){
          // this.props.userActions.GetOwnData();
          // this.props.userActions.loginComplete();
          // this.state.wait_for_response = false;



          var newData = data.object;

          Object.assign(newData, {
            for_history: false,
          });

          this.props.documentActions.DocumentLoaded(newData);
        }
        else{
          // this.setState({
          //   // is_forgot: true
          // });
          alert(data.message || "Ошибка выполнения запроса");
        }
      }.bind(this))
      .catch(function (error) {

          alert("Ошибка выполнения запроса");
        }
      );
  }


  componentWillReceiveProps(nextProps){





    // 

    if (
      !nextProps.connection_allowed
      || nextProps.disconnection_requested
    ) {
      // console.debug("Connection Close");
      this.disconnect();
    }

    else if (
      nextProps.connection_requested === true
      && !nextProps.connection_in_progress
      && !this.props.connection_in_progress
    ) {

      this.connect();
    }

    if(nextProps.user.logoutRequested && nextProps.user.logoutRequested !== this.props.user.logoutRequested){
      // if(!this.state.LogoutInProgress){

        const {
          request,
        } = this.context;
        
        // this.setState({
        //   LogoutInProgress: true
        // });

        // this.props.proxyActions.SendMessage({
        //   type: "signout",
        // });

        request(null, null, "logout", {}, {
          callback: (data, errors) => {


            
            this.props.userActions.logoutComplete();
            this.props.userActions.GetOwnData();

            // if(data.success){
            //   // this.props.userActions.GetOwnData();
            //   // this.props.userActions.loginComplete();
            //   // this.state.wait_for_response = false;
            //   this.props.userActions.GetOwnDataSuccess(data.object);
            // }
            // else{
            //   // this.setState({
            //   //   // is_forgot: true
            //   // });
            //   // alert(data.message || "Ошибка выполнения запроса");
            //   this.props.userActions.GetOwnDataFailed(data);
            // }

          },
        });

      // }
    }

    else if(
      nextProps.user.own_data_requested != this.props.user.own_data_requested
      && nextProps.user.own_data_requested == true
    ){

      const {
        // request,
        remoteQuery,
      } = this.context;

      // fetch(this.props.connector_url +'?pub_action=users/get_own_data',{
      //   credentials: 'same-origin',
      //   method: "POST",
      //   // body: body,
      // })
      //   .then(function (response) {
      //     return response.json()
      //   })
      //   .then(function (data) {
      //     // self.setState({orders: data.object});

      //     if(data.success){
      //       // this.props.userActions.GetOwnData();
      //       // this.props.userActions.loginComplete();
      //       // this.state.wait_for_response = false;
      //       this.props.userActions.GetOwnDataSuccess(data.object);
      //     }
      //     else{
      //       // this.setState({
      //       //   // is_forgot: true
      //       // });
      //       // alert(data.message || "Ошибка выполнения запроса");
      //       this.props.userActions.GetOwnDataFailed(data);
      //     }
      //   }.bind(this))
      //   // .catch(function (error) {

      //   //     alert("Ошибка выполнения запроса");
      //   //   }
      //   // );

      // request(null, null, "users/get_own_data", {}, {
      //   callback: (data, errors) => {

      //     if(data.success){
      //       // this.props.userActions.GetOwnData();
      //       // this.props.userActions.loginComplete();
      //       // this.state.wait_for_response = false;
      //       this.props.userActions.GetOwnDataSuccess(data.object);
      //     }
      //     else{
      //       // this.setState({
      //       //   // is_forgot: true
      //       // });
      //       // alert(data.message || "Ошибка выполнения запроса");
      //       this.props.userActions.GetOwnDataFailed(data);
      //     }

      //   },
      // });

      remoteQuery({
        operationName: "CurrentUser",
      })
      .then(r => {

        // console.log("WsProxy result", r);

        const {
          user,
        } = r.data || {};


        if(user){
          this.props.userActions.GetOwnDataSuccess(user);
        }
        else{
          this.props.userActions.GetOwnDataFailed(r);
        }

      });

      return false;
    }

    else if(
      nextProps.user.get_own_data_success == true
      && nextProps.user.get_own_data_success != this.props.user.get_own_data_success
      && nextProps.user.id > 0
    ){





      this.props.proxyActions.SendMessage({
        type: "joined",
        id: nextProps.user.id,
        username: nextProps.user.username,
        fullname: nextProps.user.fullname,
        photo: nextProps.user.photo,
      });

      return false;
    }

    if(this.props.outbox.length){
      if(!this.state.sending){

        /*
        * Устанавливаем признак, что сообщения в процессе отправки,
        * иначе рискуем при наличии сразу нескольких сообщений в очереди
        * отправить по нескольу раз
        * */
        this.state.sending = true;

        for(var i in this.props.outbox){
          var message = this.props.outbox[i];

          if(!message.sended){
            this.SendMessage(message);
          }
        }

        this.state.sending = false;
      }

      if(nextProps.outbox_hash != this.props.outbox_hash){

        return false;
      }
    }
    // else if(this.state.sending){
    //   this.state.sending = false;
    // }

    if(nextProps.document.requested_url && nextProps.document.requested_url != this.props.document.requested_url){
      this.load_document(nextProps.document.requested_url);
    }

    return true;
  }

  // shouldComponentUpdate(nextProps, nextState){
  //





  //
  //   // super(nextProps, nextState);
  //
  //
  //
  //
  //
  //   return true;
  // }


  // componentWillMount(){
  //   this.state.sending = true;
  // }

  render() {
 


    return <i></i>;



    /*
    *   Был запрошен догумент на загрузку
    * */



    var icon;

    if (this.props.connected) {

      icon = <IconButton
        // tooltip="Соединение установлено"
        onTouchTap={this.DisallowConnection.bind(this)
      }
      style={{
        verticalAlign: 'middle',
      }}>
        <CastConnected color={white}/>
      </IconButton>;
    }
    else if (this.props.connection_in_progress) {

      icon = <IconButton
        // tooltip="Устанавливается соединение"
        onTouchTap={this.DisallowConnection.bind(this)
      }
      style={{
        verticalAlign: 'middle',
      }}>
        <Loop color={white}/>
      </IconButton>;
    }
    else {
      if (this.props.connection_allowed) {
        icon = <IconButton
          // tooltip="Ошибка соединения"
          onTouchTap={this.RequestConnection.bind(this)
        }
        style={{
          verticalAlign: 'middle',
        }}>
          <CastConnected color={red500}/>
        </IconButton>;
      }
      else {
        icon = <IconButton
          // tooltip="Подключиться"
          onTouchTap={this.AllowConnection.bind(this)
        }
        style={{
          verticalAlign: 'middle',
        }}>
          <CastConnected />
        </IconButton>;
      }
    }

    return icon;
  }
}


WsProxy.defaultProps = defaultProps;



WsProxy.propTypes = {
  // connector_url: PropTypes.string.isRequired,
};

function mapStateToProps(state) { 


  var st = {};

  Object.assign(st, state.proxy, {
    document: state.document
  },{
    user: state.user
  });

  return st;
}


function mapDispatchToProps(dispatch) {



  return {
    proxyActions: bindActionCreators(proxyActions, dispatch),
    userActions: bindActionCreators(userActions, dispatch),
    documentActions: bindActionCreators(documentActions, dispatch),
  }
}
export default connect(mapStateToProps, mapDispatchToProps)(WsProxy);

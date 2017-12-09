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

// console.log(config);

const defaultProps = {
  host: typeof window !== "undefined" && 'ws://' + window.location.host + '/api/' || '',
};

class WsProxy extends Component {


  static defaultProps = defaultProps;

  static propTypes = {
  };

  static contextTypes = {
    connector_url: PropTypes.string.isRequired,
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
  }

  OnConnectionOpen(socket) {
  }

  SocketOnMessage(event) { 

    var incomingMessage = event.data;
    console.log('Message: %s', incomingMessage);

    var response;

    try {
      response = JSON.parse(incomingMessage);

      var type = response.type || (response.original_message ? response.original_message.type : "");

      switch (type) {
        case 'hello':
          break;


          /*
          * Этот запрос обрабатывается поисковой строчкой и прочими компонентами
          * */
        case 'form':
        case 'search':
        case 'find_user':
        case 'signup':
        case 'chat_message':
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

          break;

        case 'user/get_own_data':

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

          this.props.userActions.UpdateUsersList(response.users);
          break;

        case 'message':
          break;

        case 'introdution_confirm':

          break;

        case 'error':
          alert(response.text || "Ошибка выполнения запроса");
          break;

        case 'joined':


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

      // alert('ConnectionsInProgress');

      console.log('%s  ConnectionsInProgress', 'font-weight: bold;');

      this.ConnectionsAttempts++;

      if(this.ConnectionsBlocked){
        return false;
      }

      if(this.ConnectionsAttempts > 5){

        console.log("Слишком много попыток соединения");

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

    // console.log("load_document", url);
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
          console.log('Request failed', error);
          alert("Ошибка выполнения запроса");
        }
      );
  }


  componentWillReceiveProps(nextProps){
    // console.log('WsProxy componentWillReceiveProps(nextProps)', nextProps);
    // console.log(nextProps);
    // console.log(this.props);
    // console.log("WsProxy Render");
    // 

    const {
      connector_url,
    } = this.context;

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

    if(nextProps.user.LogoutRequested){
      if(!this.state.LogoutInProgress){
        this.setState({
          LogoutInProgress: true
        });

        this.props.proxyActions.SendMessage({
          type: "signout",
        });
      }
    }

    if(
      nextProps.user.own_data_requested != this.props.user.own_data_requested
      && nextProps.user.own_data_requested == true
    ){

      fetch(connector_url +'?pub_action=users/get_own_data',{
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
            this.props.userActions.GetOwnDataSuccess(data.object);
          }
          else{
            // this.setState({
            //   // is_forgot: true
            // });
            // alert(data.message || "Ошибка выполнения запроса");
            this.props.userActions.GetOwnDataFailed(data);
          }
        }.bind(this))
        // .catch(function (error) {
        //     console.log('Request failed', error);
        //     alert("Ошибка выполнения запроса");
        //   }
        // );

      return false;
    }

    else if(
      nextProps.user.get_own_data_success == true
      && nextProps.user.get_own_data_success != this.props.user.get_own_data_success
      && nextProps.user.id > 0
    ){

        // console.log('nextProps.user.get_own_data_success');
        // console.log(this.props);
        // console.log(nextProps);

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
  //   console.log('shouldComponentUpdate(nextProps)');
  //   // console.log(this.props);
  //   // console.log(nextProps);
  //   // console.log(this.state);
  //   // console.log(nextState);
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
 


    return <i>erge</i>;



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

function mapStateToProps(state) { 
  // console.log(state);

  var st = {};

  Object.assign(st, state.proxy, {
    document: state.document
  },{
    user: state.user
  });

  return st;
}


function mapDispatchToProps(dispatch) {
  // console.log('mapDispatchToProps');
  // console.log(dispatch);

  return {
    proxyActions: bindActionCreators(proxyActions, dispatch),
    userActions: bindActionCreators(userActions, dispatch),
    documentActions: bindActionCreators(documentActions, dispatch),
  }
}
export default connect(mapStateToProps, mapDispatchToProps)(WsProxy);

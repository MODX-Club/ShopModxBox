export const CONNECTION_ALLOWED = 'CONNECTION_ALLOWED';
export const CONNECTION_DISALLOWED = 'CONNECTION_DISALLOWED';
export const CONNECTION_REQUESTED = 'CONNECTION_REQUESTED';
export const CONNECTION_IN_PROGRESS = 'CONNECTION_IN_PROGRESS';
export const CONNECTION_FAILED = 'CONNECTION_FAILED';
export const CONNECTED = 'CONNECTED';
export const DISCONNECTED = 'DISCONNECTED';
export const MESSAGE_ADDED = 'MESSAGE_ADDED';
export const MESSAGE_RECEIVED = 'MESSAGE_RECEIVED';
export const DISCONNECTION_REQUESTED = 'DISCONNECTION_REQUESTED';
export const CONNECTION_STATE_CHANGED = 'CONNECTION_STATE_CHANGED';
export const MESSAGE_SENDED = 'MESSAGE_SENDED';
export const MESSAGE_READED = 'MESSAGE_READED';

export function AllowConnection() {
  return {
    type: CONNECTION_ALLOWED,
  };
}

export function DisallowConnection() {
  return {
    type: CONNECTION_DISALLOWED,
  };
}

export function RequestConnection() {
  return {
    type: CONNECTION_REQUESTED,
  };
}

export function disconnection_requested() {
  return {
    type: DISCONNECTION_REQUESTED,
  };
}

export function connection_in_progress() {
  return {
    type: CONNECTION_IN_PROGRESS,
  };
}

export function connection_failed() {
  return {
    type: CONNECTION_FAILED,
  };
}

export function connected() {
  return {
    type: CONNECTED,
  };
}

export function disconnected() {
  return {
    type: DISCONNECTED,
  };
}

export function SetConnectionState(connected) {
  return {
    type: CONNECTION_STATE_CHANGED,
    connected
  };
}

export function SendMessage(message) {

  return {
    type: MESSAGE_ADDED,
    message,
  };
}

export function receive_message(message) {

  return {
    type: MESSAGE_RECEIVED,
    message,
  };
}

export function MessageSended(message) {

  return {
    type: MESSAGE_SENDED,
    message,
  };
}

export function MessageReaded(message) {

  return {
    type: MESSAGE_READED,
    message,
  };
}

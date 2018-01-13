import React, {Component} from 'react';

import PropTypes from 'prop-types';

import GraphiQLProto from 'graphiql';

import Switch from 'material-ui/Switch';
import Typography from 'material-ui/Typography';


class GraphiQL extends GraphiQLProto{

  constructor(props){

    super(props);

    const {
      docExplorerOpen,
    } = props;

    Object.assign(this.state, {
      docExplorerOpen,
    });

  }

}


import {
  DbPage as SiteDbPage,
} from "modules/Site";


let {
  ...contextTypes
} = SiteDbPage.contextTypes;


Object.assign(contextTypes, {
  isDemo: PropTypes.bool.isRequired,
});


export default class DbPage extends SiteDbPage{


  static contextTypes = contextTypes;


  constructor(props){

    super(props);

    Object.assign(this.state, {
      storage: "",
    });

  }


  render(){

    if(typeof window === 'undefined'){

      return null;
    }
    
    const {
      storage,
      schema: remoteSchema,
    } = this.state;
 
    let schema;

    const {
      schema: localSchema,
      defaultQuery,
      isDemo,
    } = this.context;

    if(!isDemo){
      return super.render();
    }

    schema = localSchema;
 

    if(!schema){

      return null;
    }

    return (<div
      style={{
        display: 'flex',
        flexDirection: 'column',
        flexBasis: '100%',
        height: '100%',
      }}
    >

      <div>

        <Typography 
          type="subheading"
          style={{
            color: "red",
          }}
        >
          Конструктор API-запросов магазина.
        </Typography>

        <p>
          Измененная схема работает только в режиме "Локальное хранилище", то есть при запросах к самому браузеру. <br />
          При запросах к серверу используется схема, сохраненная на сервере. В таком случае текущая схема служит только для генерации документации
          и формирования набора доступных операций и параметров.
        </p>

        <p>
          Подробней о конструкторе запросов 
          читайте <a 
            href="https://modxclub.ru/topics/rasshirenie-api-zaprosov-v-shopmodxbox-2748.html"
            target="_blank"
          >здесь</a> и <a 
            href="https://modxclub.ru/topics/react-js.-urok-%E2%84%962.-zaprosyi-s-pomoshhyu-graphql-2693.html"
            target="_blank"
          >
          здесь</a>.
        </p>

      </div>

      <div>
        <Switch
          checked={storage == 'local'}
          onChange={(event, checked) => this.setState({
            storage: storage == 'local' ? '' : 'local',
          })}
        /> <b>Локальное хранилище: </b> 
      </div>

      <GraphiQL
        schema={schema}
        query={defaultQuery || ""}
        fetcher={::this._graphQLFetcher}
        operationName={null}
        storage={null}
        docExplorerOpen={true}
      />  
    </div>);
  }

}


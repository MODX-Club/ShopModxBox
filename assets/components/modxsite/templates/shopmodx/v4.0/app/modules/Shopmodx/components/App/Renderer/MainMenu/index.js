import React, {Component} from 'react';

import PropTypes from 'prop-types';

import { Link } from 'react-router';

import Grid from 'material-ui/Grid';
import IconButton from 'material-ui/IconButton';
import LoginIcon from 'material-ui-icons/PermIdentity';
import AddIcon from 'material-ui-icons/Add';
import AtentionIcon from 'material-ui-icons/ErrorOutline';

import Avatar from 'modules/Site/components/fields/User/avatar.js';

import WsProxy from 'modules/Shopmodx/components/WsProxy';

import cookies from 'js-cookie';

export default class MainMenu extends Component{

  static contextTypes = {
    user: PropTypes.object.isRequired,
    userActions: PropTypes.object.isRequired,
    localQuery: PropTypes.func.isRequired,
    remoteQuery: PropTypes.func.isRequired,
    router: PropTypes.object.isRequired,
    document: PropTypes.object.isRequired,
  };

	constructor(props){

		super(props);

		this.state = {
			ratingsOpened: false,
			citiesOpened: false,
		}
	}
 
 

  componentDidUpdate(prevProps, prevState, prevContext){
 

    super.componentDidUpdate && super.componentDidUpdate(prevProps, prevState, prevContext);

  }
 


  // loadData(){

  //   const {
  //     localQuery,
  //     coords,
  //   } = this.context;

  //   localQuery({
  //     operationName: "MainMenuData",
  //     variables: {
  //       limit: 0,
  //       resourcesCenter: coords,
  //     },
  //   })
  //   .then(r => {

  //     const {
  //       ratings,
  //       resources: cities,
  //     } = r.data;



  //     this.setState({
  //       ratings,
  //       cities,
  //     });

  //   })
  //   .catch(e => {
  //     console.error(e);
  //   });

  // }
  
 


	render(){

    const {
      user: {
        user,
      },
      userActions,
    } = this.context;

    const {
      username,
    } = user || {};

		let {
		} = this.state;

    let base_url = "/";


		return <div 
      className="navbar navbar-default navbar-fixed-top"
      style={{
        marginBottom: 0,
      }}
    >
      <div className="container">
        <div className="navbar-header">
          <Link 
            href={base_url}
            to={base_url}
            // href={"/"}
            // to={"/"}
            className="navbar-brand"
            title="Городские бани, главная страница"
          >
            <div className="logo">
              <i className="str leaf leaf-l"></i>
              <span className="str">Городские бани</span>
            </div>
          </Link>
          <button className="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span className="icon-bar"></span>
            <span className="icon-bar"></span>
            <span className="icon-bar"></span>
          </button>
        </div> 
        
        <div id="navbar-main" className="collapse navbar-collapse navbar-right">
          <ul 
            className="nav navbar-nav flex align-center"
            style={{
              display: "flex",
            }}
          >

                {user
                ?
                <li className="dropdown">
                  <a 
                    id="office" 
                    href="javascript:;" 
                    data-toggle="dropdown" 
                    className="dropdown-toggle flex align-center"
                    style={{
                      display: "flex",
                    }}
                  >
                    <Avatar 
                      user={user}
                      style={{
                        width: 20,
                        height: 20,
                        fontSize: "18px",
                      }}
                    />
                    <span className="caret"></span>
                  </a>
                  <ul aria-labelledby="office" className="dropdown-menu">
                    <li>
                      <Link 
                        to={`/profile/${username}`}
                        href={`/profile/${username}`}
                      >Профиль
                      </Link>
                    </li>
                    
                    {/*<li><a href="add-topic.html">Написать</a></li>*/}
                    
                    <li className="divider"></li>
                    <li>
                      <a 
                        href="javascript:;"
                        onClick={e => {
                          userActions.logout();
                        }}
                      >
                        <i className="glyphicon glyphicon-log-out"></i> Выйти
                      </a>
                    </li>
                  </ul>
                </li>
                :
                <li>
                  <a 
                    href="javascript:;" 
                    rel="nofollow"
                    onClick={event => {
                      userActions.loginClicked();
                    }}
                  >
                    <Grid 
                      container
                      gutter={0}
                      align="center"
                    >
                      <LoginIcon 
                        style={{
                          height: 16,
                          width: 16,
                        }}
                      />  Войти
                    </Grid>
                  </a>


                </li>
                
              }

          </ul>

          <WsProxy
          />
          
        </div>

      </div>
    </div>;
	}
}
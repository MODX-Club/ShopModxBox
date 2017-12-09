
import React, { Component } from 'react';

import PropTypes from 'prop-types';

import Avatar from 'material-ui/Avatar';

export default class UserAvatar extends Component{

	static propTypes = {
		user: PropTypes.object.isRequired,
	};

	static defaultProps = {
		background: "#2fa4e7",
	};

	static contextTypes = {

	};

	constructor(props){

		super(props);

		this.state = {

		};
	}

	render(){

		let {
			user,
			style,
			background,
			...other
		} = this.props;

		if(!user){
			return null;
		}

		style = style || {};

		Object.assign(style, {
			background,
		});


		const {
			
      username: author_username,
      fullname: author_fullname,
      imageFormats,
		} = user;

		const {
      thumb: author_avatar,
		} = imageFormats || {};

		return <Avatar 
    	aria-label={author_fullname || author_username || undefined}
    	className=""
  		src={author_avatar}
    	style={style}
    	{...other}
    >
      {author_avatar ? undefined : (author_fullname || author_username || '').substr(0,1).toLocaleUpperCase() }
    </Avatar>
	}
}

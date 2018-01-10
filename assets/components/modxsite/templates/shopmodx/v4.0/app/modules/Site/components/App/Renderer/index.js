import React, { Component } from 'react';

import PropTypes from 'prop-types';

import RendererPrototype from 'shopmodx-react/components/App/Renderer';

import Header from './Header';

let {
	...defaultProps
} = RendererPrototype.defaultProps || {};


Object.assign(defaultProps, {
	Header,
});


let {
	...contextTypes
} = RendererPrototype.contextTypes || {};


Object.assign(contextTypes, {
});


export default class Renderer extends RendererPrototype{

	static defaultProps = defaultProps;

	static contextTypes = contextTypes;

}

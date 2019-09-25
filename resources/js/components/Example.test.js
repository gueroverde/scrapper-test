import React from 'react';
import Enzyme from 'enzyme';
import {shallow} from 'enzyme';
import Adapter from 'enzyme-adapter-react-16.2';
import  Example from './Example';

Enzyme.configure({ adapter: new Adapter() });


test('render example', () => {
    // Render a div with label in the document
    const div = shallow(
        <Example />,
    );

    expect(div.text()).toMatch('Example ComponentI\'m an example component!');
});
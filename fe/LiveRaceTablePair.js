import React from 'react';

export default class LiveRaceTablePair extends React.Component {

    constructor() {
        super();
    }

    fullName(member) {
        return [member.name, member.surname].join(' ') + (member.nickname !== null ? ' (' + member.nickname +')' : '');
    }

    render() {

        return (
            <tbody>
               <tr>
                   <td rowSpan={this.props.members.length}>
                       {this.props.rank}.
                   </td>
                   <td rowSpan={this.props.members.length}>
                       [{this.props.startNum}]
                   </td>
                   <td>
                       {this.fullName(this.props.members[0])}
                   </td>
               </tr>
               <tr>
                   <td>
                       {this.fullName(this.props.members[1])}
                   </td>
               </tr>
            </tbody>
        );
    }

}
import React from 'react';
import './LiveRaceTablePair.less';

export default class LiveRaceTablePair extends React.Component {

    constructor() {
        super();

        this.toFirst = this.toFirst.bind(this);
    }

    fullName(member) {
        return [member.name, member.surname].join(' ') + (member.nickname !== null ? ' (' + member.nickname +')' : '');
    }


    toFirst() {
        this.props.toFirst(this.props.id);
    }

    render() {

        const style = {
            top: (this.props.rank-1) * 100,
            left: 0
        };

        return (
            <table className="table table-striped pair" style={style}>
               <tr>
                   <td rowSpan={this.props.members.length} width="100">
                       {this.props.rank}.
                   </td>
                   <td rowSpan={this.props.members.length} width="100">
                       [{this.props.startNum}]
                   </td>
                   <td>
                       {this.fullName(this.props.members[0])}
                   </td>
                   <td rowSpan={this.props.members.length} width="100">
                       <button className="btn btn-warning" onClick={this.toFirst}>Na první</button>
                   </td>
               </tr>
               <tr>
                   <td>
                       {this.fullName(this.props.members[1])}
                   </td>
               </tr>
            </table>
        );
    }

}
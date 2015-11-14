import React from 'react';
import LiveRaceTablePair from './LiveRaceTablePair.js'

export default class LiveRaceTable extends React.Component {

    constructor() {
        super();

        this.state = {
            pairs: [
                {
                    id: 1,
                    startNum: 1,
                    members: [
                        {
                            name: 'Petr',
                            surname: 'Sladek',
                            nickname: 'Peggy',
                        },
                        {
                            name: 'Tomáš',
                            surname: 'Páteček',
                            nickname: 'Friday'
                        }
                    ],
                    rank: 1
                },
                {
                    id: 2,
                    startNum: 2,
                    members: [
                        {
                            name: 'Libor',
                            surname: 'Čermák',
                            nickname: null
                        },
                        {
                            name: 'Tereza',
                            surname: 'Kociánová',
                            nickname: 'Kuře'
                        }
                    ],
                    rank: 2
                },
                {
                    id: 3,
                    startNum: 3,
                    members: [
                        {
                            name: 'Anna',
                            surname: 'Jančevová',
                            nickname: null
                        },
                        {
                            name: 'Kristýna',
                            surname: 'Nevím',
                            nickname: null
                        }
                    ],
                    rank: 3
                },
                {
                    id: 4,
                    startNum: 4,
                    members: [
                        {
                            name: 'Nekdo',
                            surname: 'Jinej',
                            nickname: 'Dalsší'
                        },
                        {
                            name: 'Nekdo',
                            surname: 'Jinej',
                            nickname: 'Dalsší'
                        }
                    ],
                    rank: 4
                },
            ]
        };

        this.randomOrder = this.randomOrder.bind(this);
    }

    randomOrder(e)  {

        var pairs = this.state.pairs;

        pairs = pairs.map(function(pair) {
            pair.rank = Math.floor(Math.random() * pairs.length ) + 1;
            return pair;
        });

        pairs.sort((a,b) => {
            return a.rank - b.rank
        });


        pairs = pairs.map(function(pair, i) {
            pair.rank = i + 1;
            return pair;
        });

        this.setState({
            pairs: pairs
        })
    }

    render() {
        return (
            <div>
                <table className="table table-tbody-striped">
                    {this.state.pairs.map(pair => {
                        return <LiveRaceTablePair
                                    members={pair.members}
                                    startNum={pair.startNum}
                                    rank={pair.rank}
                                    key={pair.id} />;
                    })}
                </table>
                <button className="btn btn-info" onClick={this.randomOrder}>Náhodné pořadí</button>
            </div>
        );
    }



}
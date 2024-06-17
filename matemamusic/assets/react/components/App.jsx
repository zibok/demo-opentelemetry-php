import React, { useEffect, useState } from 'react';
import TopBar from './TopBar';

function loadingOrTopBar(loading, users) {
    if (loading) {
        return <h1>Loading...</h1>
    } else {
        return <TopBar users={users} />
    }
}

export default function App() {
    const [users, setUsers] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {fetchUsers()}, []);

    const fetchUsers = async () => {
        try {
            const response = await fetch("/users/list", {
                "headers": {
                    "Accept": "application/json"
                }
            }).then(r => r.json());

            setUsers(response.users);
            setLoading(false);
        } catch (err) {
            console.log(err)
        }
    };

    return (
        <div>
        {loadingOrTopBar(loading, users)}
        </div>
    )
}

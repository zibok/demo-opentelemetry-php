import React, { useEffect, useState } from 'react';
import TopBar from './TopBar';
import PlaylistBoard from './PlaylistBoard';

export default function App() {
    const [users, setUsers] = useState([]);
    const [currentUser, setCurrentUser] = useState({ id: 0, name: ""});
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

    const handleUserChange = (event) => {
        const [id, name] = event.target.value.split("/")
        setCurrentUser({id: id, name: name});
    } 

    if (loading) {
        return <h1>Loading...</h1>
    } else {
        return (
            <div>
                <TopBar users={users} onUserChange={handleUserChange}/>
                <PlaylistBoard currentUser={currentUser}/>
           </div>
        )
    } 
}

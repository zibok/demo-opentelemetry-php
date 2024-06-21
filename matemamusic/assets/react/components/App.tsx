import React, { useEffect, useState } from 'react';
import TopBar from './TopBar';
import PlaylistBoard from './PlaylistBoard';
import {CircularProgress, SelectChangeEvent} from "@mui/material";
import {User} from "../types/User";

export default function App() {
    const [users, setUsers] = useState<User[]>([]);
    const [currentUser, setCurrentUser] = useState<User>({ id: 0, name: ""} as User);
    const [loading, setLoading] = useState<boolean>(true);

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

    const handleUserChange = (event: SelectChangeEvent<string>) => {
        const [id, name] = event.target.value.split("/")
        setCurrentUser({id: parseInt(id), name: name} as User);
    } 

    if (loading) {
        return <CircularProgress />
    } else {
        return (
            <div>
                <TopBar users={users} onUserChange={handleUserChange}/>
                <PlaylistBoard currentUser={currentUser}/>
           </div>
        )
    } 
}

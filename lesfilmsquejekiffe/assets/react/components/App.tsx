import React, { useEffect, useState } from 'react';
import TopBar from './TopBar';
import FavlistBoard from './FavlistBoard';
import {Box, CircularProgress, CssBaseline, SelectChangeEvent, ThemeProvider, createTheme} from "@mui/material";
import {User} from "../types/User";

const defaultTheme = createTheme();

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
            <ThemeProvider theme={defaultTheme}>
                <CssBaseline />
                <Box>
                    <TopBar users={users} onUserChange={handleUserChange}/>
                    <FavlistBoard currentUser={currentUser}/>
                </Box>
           </ThemeProvider>
        )
    } 
}

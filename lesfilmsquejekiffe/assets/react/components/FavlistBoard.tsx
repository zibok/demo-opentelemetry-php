import React, {ReactNode, useEffect, useState} from "react";
import {User} from "../types/User";
import {Favlist} from "../types/Favlist";
import {Box, Button, CircularProgress, Grid, Link, Paper, Typography} from "@mui/material";
import FavlistCreationModal from "./FavlistCreationModal";
import FavlistItem from "./FavlistItem";

export type FavlistBoardProps = {
    currentUser: User;
}

function listOfFavlists(favlists: Favlist[], handleClose: (event: Object, reason: string) => void): ReactNode {
    if (favlists.length == 0) {
        return (
            <Paper elevation={3}>
            Cette utilisateur n'a pas de liste de favoris pour le moment.
            </Paper>
        );
    }
    
    return (
        <Grid container spacing={2}>
        {favlists.map(item => <FavlistItem favlist={item} onClose={handleClose} />)}
        </Grid>
    );
}

export default function FavlistBoard(props: FavlistBoardProps) {
    const [favlists, setFavlists] = useState<Favlist[]>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [modalOpen, setModalOpen] = useState(false);
    const handleClose = (event: Object, reason?: string) => {
        if (reason !== 'backdropClick') {
            setModalOpen(false);
            refresh();
        }
    }
    
    const refresh = () => {
        setLoading(true);
        if (props.currentUser.id > 0) {
            fetchFavlistForUser(props.currentUser.id)
        }
    }
    
    useEffect(
        () => {refresh()},
        [props.currentUser.id]
    );
    
    const fetchFavlistForUser = async (userId: number) => {
        try {
            const response = await fetch(`/users/${userId}/favlists`, {
                "headers": {
                    "Accept": "application/json"
                }
            }).then(r => r.json());
            
            setFavlists(response.favlists);
            setLoading(false);
        } catch (err) {
            console.log(err)
        }
    };
    
    if (props.currentUser.id === 0) {
        return (
            <Typography component="h1" variant="h4">Commencez par sélectionner un utilisateur dans la barre au-dessus</Typography>
        )
    }
    
    if (loading) {
        return (
            <Box alignContent={"center"}
            width={"100%"}>
            <CircularProgress />
            </Box>
        );
    }
    
    return (
        <Box width={"100%"} component="main" sx={{ flexGrow: 1 }} padding="10px">
            <Box sx={{display: "flex", paddingTop: "10px", paddingBottom: "10px"}}>
                <Typography variant="h6" paddingRight="1em">{"Listes de favoris de " + props.currentUser.name}</Typography>
                <Button variant="contained" onClick={() => {setModalOpen(true)}}>Créer une liste de favoris</Button>
            </Box>
            <FavlistCreationModal
            open={modalOpen}
            onClose={handleClose}
            favlistOwnerId={props.currentUser.id}
            />
            {listOfFavlists(favlists, handleClose)}
        </Box>
    );
}

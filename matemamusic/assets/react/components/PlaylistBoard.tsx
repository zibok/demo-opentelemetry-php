import React, {ReactNode, useEffect, useState} from "react";
import {User} from "../types/User";
import {Playlist} from "../types/Playlist";
import {Box, Button, CircularProgress, Grid, Paper, Typography} from "@mui/material";
import PlaylistCreationModal from "./PlaylistCreationModal";
import MusicNoteIcon from '@mui/icons-material/MusicNote';

export type PlaylistBoardProps = {
    currentUser: User;
}

function listOfPlaylists(playlists: Playlist[]): ReactNode {
    if (playlists.length == 0) {
        return (
            <Paper elevation={3}>
                No playlist for the moment
            </Paper>
        );
    }

    return (
        <Grid container spacing={1}>
            {playlists.map(item => {
                return (
                    <Grid item xs={12} sm={6}>
                    <Paper key={`playlist-${item.id}`} elevation={3}>
                        <Typography component="h2" variant="h6"><MusicNoteIcon/>{item.name}</Typography>
                        <div>Soon the tracks</div>
                    </Paper>
                    </Grid>
                )
            })}
        </Grid>
    );
}

export default function PlaylistBoard(props: PlaylistBoardProps) {
    const [playlists, setPlaylists] = useState<Playlist[]>([]);
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
            fetchPlaylistForUser(props.currentUser.id)
        }
    }

    useEffect(
        () => {refresh()},
        [props.currentUser.id]
    );

    const fetchPlaylistForUser = async (userId: number) => {
        try {
            const response = await fetch(`/users/${userId}/playlists`, {
                "headers": {
                    "Accept": "application/json"
                }
            }).then(r => r.json());

            setPlaylists(response.playlists);
            setLoading(false);
        } catch (err) {
            console.log(err)
        }
    };

    if (props.currentUser.id === 0) {
        return (
            <h1>{"Please, select a user"}</h1>
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
        <Box width={"100%"} component="main" sx={{ flexGrow: 1 }}>
            <Box sx={{display: "flex", paddingTop: "10px", paddingBottom: "10px"}}>
                <Typography variant="h6" paddingRight="1em">{"Playlists of user " + props.currentUser.name}</Typography>
                <Button variant="contained" onClick={() => {setModalOpen(true)}}>Create a new playlist</Button>
            </Box>
            <PlaylistCreationModal
                open={modalOpen}
                onClose={handleClose}
                playlistOwnerId={props.currentUser.id}
            />
            {listOfPlaylists(playlists)}
        </Box>
    );
}

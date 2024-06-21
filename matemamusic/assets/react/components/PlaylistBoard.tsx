import React, {useEffect, useState} from "react";
import {User} from "../types/User";
import {Playlist} from "../types/Playlist";
import {Box, Button, CircularProgress, List, ListItem, Modal, Typography} from "@mui/material";
import PlaylistCreationModal from "./PlaylistCreationModal";

export type PlaylistBoardProps = {
    currentUser: User;
}

function listOfPlaylists(playlists: Playlist[]) {
    if (playlists.length > 0) {
        return (
            <List>
                {playlists.map(item => {
                    return <ListItem key={`playlist-${item.id}`}>{item.name}</ListItem>
                })}
            </List>
        );
    }
}

export default function PlaylistBoard(props: PlaylistBoardProps) {
    const [playlists, setPlaylists] = useState<Playlist[]>([]);
    const [loading, setLoading] = useState<boolean>(true);
    const [modalOpen, setModalOpen] = useState(false);
    const handleClose = () => {
        setModalOpen(false);
        refresh();
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
        <Box width={"100%"}>
            <Typography variant="h2">{"Playlists of user " + props.currentUser.name}</Typography>
            <Button variant="contained" onClick={() => {setModalOpen(true)}}>Create a new playlist</Button>
            <PlaylistCreationModal
                open={modalOpen}
                onClose={handleClose}
                playlistOwnerId={props.currentUser.id}
            />
            {listOfPlaylists(playlists)}
        </Box>
    );
}

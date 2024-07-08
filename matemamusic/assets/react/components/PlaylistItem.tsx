import React, { ReactNode, useState } from 'react';
import { Playlist } from '../types/Playlist';
import { Button, Grid, Link, Paper, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Typography } from '@mui/material';
import MusicNoteIcon from '@mui/icons-material/MusicNote';
import { DataGrid, GridColDef } from '@mui/x-data-grid';
import AddTrackToPlaylistModal from './AddTrackToPlaylistModal';

type PlaylistItemProps = {
    playlist: Playlist;
}

const columns: GridColDef[] = [
    { field: 'id', headerName: '#', width: 50 },
    { field: 'title', headerName: 'Title', width: 315 },
    { field: 'author', headerName: 'Author', width: 250 },
    { 
        field: 'link', 
        headerName: 'Link', 
        renderCell: (params) => ( 
            <Link href={params.row.link}>Go</Link>
        ),
     },
];
  
export default function PlaylistItem(props: PlaylistItemProps): ReactNode {
    const [modalOpen, setModalOpen] = useState<boolean>(false);

    const handleClose = (event: Object, reason?: string) => {
        if (reason !== 'backdropClick') {
            setModalOpen(false);
            //refresh();
        }
    }

    const rows = props.playlist.trackList.map((track, index) => {
        return {
            id: index + 1,
            ...track,
        };
    })

    return (
        <Grid item xs={12} sm={6} key={`playlist-${props.playlist.id}`}>
        <Paper elevation={3}>
            <Typography component="h2" variant="h6"><MusicNoteIcon />{props.playlist.name}</Typography>
            <Button variant='text' onClick={() => {setModalOpen(true)}}>Add a track</Button>
            <AddTrackToPlaylistModal
                open={modalOpen}
                onClose={handleClose}
                playlistId={props.playlist.id}
            />
            <DataGrid 
                autoHeight
                columns={columns}
                rows={rows}
                initialState={{
                    pagination: {
                        paginationModel: { page: 0, pageSize: 5 },
                    },
                }}
                pageSizeOptions={[5, 10]}
            />
        </Paper>
        </Grid>
    )
}